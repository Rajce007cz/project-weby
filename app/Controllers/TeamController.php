<?php
namespace App\Controllers;

use App\Models\TeamModel;
use App\Models\ResultModel;
use App\Models\SeasonModel;
use App\Models\DriverModel;



class TeamController extends BaseController
{
    public function index()
    {
        $teamModel = new TeamModel();
        $teams = $teamModel->orderBy('points', 'DESC')->findAll();

        return view('teams/teams', ['teams' => $teams]);
    }
    
    public function seasonResults($teamId, $year)
{
    $db = \Config\Database::connect();

    // Získat informace o týmu
    $team = $db->table('teams')->where('id', $teamId)->get()->getRow();

    if (!$team) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException("Tým nebyl nalezen.");
    }

    // Souhrnné výsledky jezdců týmu v dané sezóně
    $seasonResults = $db->table('results r')
        ->select('d.id as driver_id, d.first_name, d.last_name, SUM(r.points) as total_points, COUNT(CASE WHEN r.position = 1 THEN 1 END) as wins')
        ->join('drivers d', 'd.id = r.driver_id')
        ->join('races ra', 'ra.id = r.race_id')
        ->where('r.team_id', $teamId)
        ->where('ra.season_year', $year)
        ->groupBy('d.id, d.first_name, d.last_name')
        ->orderBy('total_points', 'DESC')
        ->get()
        ->getResultArray();

    // Seznam závodů v sezóně
    $raceList = $db->table('races')
        ->select('id, name')
        ->where('season_year', $year)
        ->orderBy('date', 'ASC')
        ->get()
        ->getResultArray();

    // Detailní výsledky: jezdec x závod x body
    $rawResults = $db->table('results r')
        ->select('r.driver_id, r.race_id, r.points, r.position, d.first_name, d.last_name')
        ->join('drivers d', 'd.id = r.driver_id')
        ->join('races ra', 'ra.id = r.race_id')
        ->where('r.team_id', $teamId)
        ->where('ra.season_year', $year)
        ->orderBy('d.last_name')
        ->get()
        ->getResultArray();

    // Přeskládání dat do pivotní tabulky
    $resultsByDriver = [];
$driverNames = [];

foreach ($rawResults as $row) {
    $resultsByDriver[$row['driver_id']][$row['race_id']] = [
        'points' => (int)$row['points'],  // tady přetypování na int
        'position' => $row['position'],
    ];
    $driverNames[$row['driver_id']] = $row['first_name'] . ' ' . $row['last_name'];
}

// Ořezání desetin u total_points
foreach ($seasonResults as &$row) {
    $row['total_points'] = (int)$row['total_points'];
}
    

    return view('teams/seasons', [
        'team' => $team,
        'year' => $year,
        'seasonResults' => $seasonResults,
        'raceList' => $raceList,
        'resultsByDriver' => $resultsByDriver,
        'driverNames' => $driverNames,
    ]);
}
}