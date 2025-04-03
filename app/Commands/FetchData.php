<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use GuzzleHttp\Client;
use App\Models\SeasonModel;
use App\Models\DriverModel;
use App\Models\TeamModel;
use App\Models\RaceModel;
use App\Models\ResultModel;

class FetchData extends BaseCommand
{
    protected $group = 'F1';
    protected $name = 'fetch:f1data';
    protected $description = 'Fetches F1 data from Ergast API and saves it into the database';

    public function run(array $params)
    {
        $client = new Client();
        $seasonModel = new SeasonModel();
        $driverModel = new DriverModel();
        $teamModel = new TeamModel();
        $raceModel = new RaceModel();
        $resultModel = new ResultModel();

        for ($year = 2000; $year <= 2025; $year++) {
            CLI::write("Fetching data for season: $year", 'green');

            // Fetch season data
            $response = $client->request('GET', "http://ergast.com/api/f1/$year/driverStandings.json");
            $data = json_decode($response->getBody(), true);

            // Extract season description
            $description = "F1 season $year summary."; // TODO: Možno doplnit lepší popis
            $seasonModel->insert(['year' => $year, 'description' => $description]);

            // Process drivers and teams
            $standings = $data['MRData']['StandingsTable']['StandingsLists'][0]['DriverStandings'] ?? [];
            foreach ($standings as $driver) {
                $driverData = [
                    'first_name' => $driver['Driver']['givenName'],
                    'last_name' => $driver['Driver']['familyName'],
                    'nationality' => $driver['Driver']['nationality'],
                    'dob' => $driver['Driver']['dateOfBirth'],
                    'points' => $driver['points'],
                    'win' => $driver['wins'],
                    'wdc' => 0, // TODO: Počítat počet WDC na základě dat
                    'image' => '', // TODO: Přidat URL obrázku pokud je dostupný
                ];
                $driverModel->insert($driverData);
            }

            // Fetch races
            $raceResponse = $client->request('GET', "http://ergast.com/api/f1/$year.json");
            $raceData = json_decode($raceResponse->getBody(), true);
            foreach ($raceData['MRData']['RaceTable']['Races'] as $race) {
                $raceModel->insert([
                    'name' => $race['raceName'],
                    'country' => $race['Circuit']['Location']['country'],
                    'date' => $race['date'],
                    'season_year' => $year,
                ]);
            }
        }
        CLI::write("Data fetching completed!", 'yellow');
    }
}