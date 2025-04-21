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
    protected $description = 'F1 data 2000-2024';

    protected function getPointsRules($year)
    {
        if ($year >= 2010) {
            return [25, 18, 15, 12, 10, 8, 6, 4, 2, 1];
        } elseif ($year >= 2003) {
            return [10, 8, 6, 5, 4, 3, 2, 1];
        } else {
            return [10, 6, 4, 3, 2, 1];
        }
    }

    protected function generateImageName(string $firstName, string $lastName): string
{
    $fullName = strtolower($firstName . ' ' . $lastName);

    $diacritics = [
        'á'=>'a', 'č'=>'c', 'ď'=>'d', 'é'=>'e', 'ě'=>'e', 'í'=>'i', 'ň'=>'n', 'ó'=>'o', 'ř'=>'r', 'š'=>'s', 'ť'=>'t', 'ú'=>'u', 'ů'=>'u', 'ý'=>'y', 'ž'=>'z', 'ä'=>'a', 'ö'=>'o', 'ü'=>'u', 'ß'=>'ss', 'ñ'=>'n', 'ø'=>'o', 'å'=>'a', 'æ'=>'ae', 'œ'=>'oe', 'â'=>'a', 'ê'=>'e', 'î'=>'i', 'ô'=>'o', 'û'=>'u', 'ë'=>'e', 'ï'=>'i', 'ç'=>'c', 'á'=>'a', 'à'=>'a', 'è'=>'e', 'ì'=>'i', 'ò'=>'o', 'ù'=>'u',];

    $cleaned = strtr($fullName, $diacritics);

    // Tohle by už snad mohlo fungovat
    $snake = preg_replace('/[^a-z0-9]+/', '_', $cleaned);

    return trim($snake, '_') . '.png';
}


    public function run(array $params)
    {
        $client = new Client(['base_uri' => 'https://ergast.com/api/f1/']);

        $driverModel = new DriverModel();
        $teamModel = new TeamModel();
        $seasonModel = new SeasonModel();
        $raceModel = new RaceModel();
        $resultModel = new ResultModel();

        for ($year = 2000; $year <= 2025; $year++) {
            CLI::write("Zpracovávání sezóny $year", 'yellow');

            $seasonModel->insert(['year' => $year]);

            $res = $client->get("$year.json");
            $races = json_decode($res->getBody()->getContents(), true);
            $racesList = $races['MRData']['RaceTable']['Races'] ?? [];

            $driverStats = [];
            $teamStats = [];

            $pointsRules = $this->getPointsRules($year);

            foreach ($racesList as $race) {
                $raceData = [
                    'season_year' => $year,
                    'name'        => $race['raceName'],
                    'country'     => $race['Circuit']['Location']['country'],
                    'date'        => $race['date'],
                ];
                $raceModel->insert($raceData);
                $raceId = $raceModel->getInsertID();

                $resultsRes = $client->get("$year/{$race['round']}/results.json");
                $results = json_decode($resultsRes->getBody()->getContents(), true);
                $resultsList = $results['MRData']['RaceTable']['Races'][0]['Results'] ?? [];

                foreach ($resultsList as $result) {
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

                    if ($existingDriver) {
                        $driverId = $existingDriver['id'];
                    } else {
                        $driverData['image'] = $this->generateImageName(
                            $driverData['first_name'],
                            $driverData['last_name']
                        );
                        $driverId = $driverModel->insert($driverData, true);
                    }

                    $constructor = $result['Constructor'];
                    $teamData = [
                        'name'       => $constructor['name'],
                        'nationality'=> $constructor['nationality'],
                    ];

                    $existingTeam = $teamModel->where('name', $teamData['name'])->first();
                    $teamId = $existingTeam ? $existingTeam['id'] : $teamModel->insert($teamData, true);

                    $position = (int)$result['position'];
                    $points = $pointsRules[$position - 1] ?? 0;

                    $resultModel->insert([
                        'race_id'   => $raceId,
                        'driver_id' => $driverId,
                        'team_id'   => $teamId,
                        'position'  => $position,
                        'points'    => $points
                    ]);

                    $driverStats[$driverId]['points'] = ($driverStats[$driverId]['points'] ?? 0) + $points;
                    $driverStats[$driverId]['wins'] = ($driverStats[$driverId]['wins'] ?? 0) + ($position === 1 ? 1 : 0);

                    $teamStats[$teamId]['points'] = ($teamStats[$teamId]['points'] ?? 0) + $points;
                    $teamStats[$teamId]['wins'] = ($teamStats[$teamId]['wins'] ?? 0) + ($position === 1 ? 1 : 0);
                    $teamStats[$teamId]['podiums'] = ($teamStats[$teamId]['podiums'] ?? 0) + (in_array($position, [1, 2, 3]) ? 1 : 0);
                }
            }

            // Přičítání
            foreach ($driverStats as $id => $stat) {
                $current = $driverModel->find($id);
                $driverModel->update($id, [
                    'points' => $current['points'] + $stat['points'],
                    'win'    => $current['win'] + $stat['wins'],
                ]);
            }

            foreach ($teamStats as $id => $stat) {
                $current = $teamModel->find($id);
                $teamModel->update($id, [
                    'points'  => $current['points'] + $stat['points'],
                    'wins'    => $current['wins'] + $stat['wins'],
                    'podiums' => $current['podiums'] + $stat['podiums'],
                ]);
            }

            // WDC
            arsort($driverStats);
            $wdcId = array_key_first($driverStats);
            if ($wdcId) {
                $driver = $driverModel->find($wdcId);
                $driverModel->update($wdcId, ['wdc' => $driver['wdc'] + 1]);
            }

            // WCC
            arsort($teamStats);
            $wccId = array_key_first($teamStats);
            if ($wccId) {
                $team = $teamModel->find($wccId);
                $teamModel->update($wccId, ['wcc' => $team['wcc'] + 1]);
            }
        }

        CLI::write('Data byla úspěšně uložena.', 'green');
    }
}