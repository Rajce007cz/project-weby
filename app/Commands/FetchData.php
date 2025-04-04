<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\DriverModel;
use App\Models\TeamModel;
use App\Models\SeasonModel;
use App\Models\RaceModel;
use App\Models\ResultModel;
use GuzzleHttp\Client;

class FetchData extends BaseCommand
{
    protected $group       = 'F1';
    protected $name        = 'fetch:f1data';
    protected $description = 'Načte F1 data ze sezón 2000–2025 z Ergast API a uloží je do databáze.';

    public function run(array $params)
    {
        $client = new Client(['base_uri' => 'https://ergast.com/api/f1/']);

        $driverModel = new DriverModel();
        $teamModel = new TeamModel();
        $seasonModel = new SeasonModel();
        $raceModel = new RaceModel();
        $resultModel = new ResultModel();

        for ($year = 2000; $year <= 2025; $year++) {
            CLI::write("Zpracovávám sezónu $year", 'yellow');

            // Uložit sezónu
            $seasonModel->insert(['year' => $year]);

            // Načti závody v sezóně
            $res = $client->get("$year.json");
            $races = json_decode($res->getBody()->getContents(), true);
            $racesList = $races['MRData']['RaceTable']['Races'] ?? [];

            $driverStats = [];
            $teamStats = [];

            foreach ($racesList as $race) {
                $raceData = [
                    'season_year' => $year,
                    'name'        => $race['raceName'],
                    'country'     => $race['Circuit']['Location']['country'],
                    'date'        => $race['date'],
                ];
                $raceModel->insert($raceData);
                $raceId = $raceModel->getInsertID();

                // Výsledky závodu
                $resultsRes = $client->get("$year/{$race['round']}/results.json");
                $results = json_decode($resultsRes->getBody()->getContents(), true);
                $resultsList = $results['MRData']['RaceTable']['Races'][0]['Results'] ?? [];

                foreach ($resultsList as $result) {
                    // Ulož jezdce
                    $driver = $result['Driver'];
                    $driverData = [
                        'first_name' => $driver['givenName'],
                        'last_name'  => $driver['familyName'],
                        'nationality'=> $driver['nationality'],
                        'dob'        => $driver['dateOfBirth'],
                    ];

                    $existingDriver = $driverModel
                        ->where('first_name', $driverData['first_name'])
                        ->where('last_name', $driverData['last_name'])
                        ->first();

                    $driverId = $existingDriver ? $existingDriver['id'] : $driverModel->insert($driverData, true);

                    // Ulož tým
                    $constructor = $result['Constructor'];
                    $teamData = [
                        'name'       => $constructor['name'],
                        'nationality'=> $constructor['nationality'],
                    ];

                    $existingTeam = $teamModel->where('name', $teamData['name'])->first();
                    $teamId = $existingTeam ? $existingTeam['id'] : $teamModel->insert($teamData, true);

                    // Výsledek
                    $resultModel->insert([
                        'race_id'   => $raceId,
                        'driver_id' => $driverId,
                        'team_id'   => $teamId,
                        'position'  => $result['position'],
                        'points'    => $result['points']
                    ]);

                    // Sbírej statistiky
                    $driverKey = $driverId;
                    $teamKey = $teamId;

                    if (!isset($driverStats[$driverKey])) {
                        $driverStats[$driverKey] = ['points' => 0, 'wins' => 0];
                    }
                    $driverStats[$driverKey]['points'] += floatval($result['points']);
                    if ($result['position'] === '1') {
                        $driverStats[$driverKey]['wins'] += 1;
                    }

                    if (!isset($teamStats[$teamKey])) {
                        $teamStats[$teamKey] = ['points' => 0, 'wins' => 0, 'podiums' => 0];
                    }
                    $teamStats[$teamKey]['points'] += floatval($result['points']);
                    if ($result['position'] === '1') {
                        $teamStats[$teamKey]['wins'] += 1;
                    }
                    if (in_array($result['position'], ['1', '2', '3'])) {
                        $teamStats[$teamKey]['podiums'] += 1;
                    }
                }
            }

            // Aktualizuj jezdce a týmy statistikami
            foreach ($driverStats as $id => $stat) {
                $driverModel->update($id, [
                    'points' => $stat['points'],
                    'win'    => $stat['wins'],
                ]);
            }

            foreach ($teamStats as $id => $stat) {
                $teamModel->update($id, [
                    'points'  => $stat['points'],
                    'wins'    => $stat['wins'],
                    'podiums' => $stat['podiums'],
                ]);
            }

            // WDC = jezdec s nejvíce body
            arsort($driverStats);
            $wdcId = array_key_first($driverStats);
            if ($wdcId) {
                $driver = $driverModel->find($wdcId);
                $driverModel->update($wdcId, ['wdc' => $driver['wdc'] + 1]);
            }

            // WCC = tým s nejvíce body
            arsort($teamStats);
            $wccId = array_key_first($teamStats);
            if ($wccId) {
                $team = $teamModel->find($wccId);
                $teamModel->update($wccId, ['wcc' => $team['wcc'] + 1]);
            }
        }

        CLI::write('✅ Hotovo! Data byla úspěšně uložena.', 'green');
    }
}