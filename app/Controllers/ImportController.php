<?php

namespace App\Controllers;

use App\Models\DriverModel;
use App\Models\TeamModel;
use App\Models\RaceModel;
use App\Models\ResultModel;
use CodeIgniter\Controller;
use GuzzleHttp\Client;

class ImportController extends Controller
{
    public function importData()
    {
        // Nastavení Guzzle klienta
        $client = new Client();

        // Sezóna, kterou chceme stáhnout (například 2020)
        $seasons = range(2000, 2025);

        foreach ($seasons as $season) {
            // Získání dat o závodech pro daný rok
            $response = $client->get("https://ergast.com/api/f1/{$season}.json");
            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['MRData']['RaceTable']['Races'])) {
                foreach ($data['MRData']['RaceTable']['Races'] as $race) {
                    // Uložení závodu
                    $this->saveRace($race, $season);
                    // Uložení výsledků závodu (po závodě)
                    $this->saveRaceResults($race);
                }
            }
        }

        echo "Data byla úspěšně stažena a uložena!";
    }

    private function saveRace($race, $season)
    {
        $raceModel = new RaceModel();

        // Uložení závodu
        $raceModel->save([
            'name' => $race['raceName'],
            'country' => $race['Circuit']['Location']['country'],
            'date' => $race['date'],
            'season_year' => $season,
        ]);
    }

    private function saveRaceResults($race)
    {
        $resultModel = new ResultModel();
        $driverModel = new DriverModel();
        $teamModel = new TeamModel();

        foreach ($race['Results'] as $result) {
            // Uložení výsledků (pozice, body)
            $resultModel->save([
                'race_id' => $race['raceId'],  // Odkaz na závod
                'driver_id' => $this->getDriverId($result['Driver']),
                'team_id' => $this->getTeamId($result['Constructor']),
                'position' => $result['position'],
                'points' => $result['points'],
            ]);
        }
    }

    private function getDriverId($driver)
    {
        // Zkontroluj, jestli už je řidič v databázi, pokud není, přidej ho
        $driverModel = new DriverModel();
        $existingDriver = $driverModel->where('first_name', $driver['givenName'])
                                      ->where('last_name', $driver['familyName'])
                                      ->first();

        if (!$existingDriver) {
            // Ulož nového jezdce
            $driverModel->save([
                'first_name' => $driver['givenName'],
                'last_name' => $driver['familyName'],
                'nationality' => $driver['nationality'],
                'dob' => $driver['dateOfBirth'] ?? null,
                'points' => 0,
                'wins' => 0,
                'wdc' => 0,
                'image' => null,  // Možná později přidáš obrázek
            ]);
            $existingDriver = $driverModel->where('first_name', $driver['givenName'])
                                          ->where('last_name', $driver['familyName'])
                                          ->first();
        }

        return $existingDriver['id'];
    }

    private function getTeamId($constructor)
    {
        // Zkontroluj, jestli už je tým v databázi, pokud není, přidej ho
        $teamModel = new TeamModel();
        $existingTeam = $teamModel->where('name', $constructor['name'])->first();

        if (!$existingTeam) {
            // Ulož nový tým
            $teamModel->save([
                'name' => $constructor['name'],
                'nationality' => $constructor['nationality'],
                'points' => 0,
                'wins' => 0,
                'podiums' => 0,
                'wcc' => 0,
            ]);
            $existingTeam = $teamModel->where('name', $constructor['name'])->first();
        }

        return $existingTeam['id'];
    }
}