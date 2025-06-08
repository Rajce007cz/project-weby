<?php

namespace App\Controllers;

use App\Models\SeasonModel;
use App\Models\ResultModel;
use CodeIgniter\Controller;

class SeasonController extends Controller
{
    protected $seasonModel;
    protected $db;

    public function __construct()
    {
        $this->seasonModel = new SeasonModel();
        $this->resultModel = new ResultModel();
        $this->db = \Config\Database::connect();
    }

  public function index()
{
    $seasons = $this->seasonModel->orderBy('year', 'DESC')->findAll();

    $seasonData = [];

    foreach ($seasons as $season) {
        $year = $season['year'];

        // Vítězný jezdec
        $topDriver = $this->db->table('results r')
            ->select('d.first_name, d.last_name, SUM(r.points) AS points')
            ->join('drivers d', 'd.id = r.driver_id')
            ->join('races ra', 'ra.id = r.race_id')
            ->where('ra.season_year', $year)
            ->groupBy('d.id')
            ->orderBy('points', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        // Vítězný tým
        $topTeam = $this->db->table('results r')
            ->select('t.name, SUM(r.points) AS points')
            ->join('teams t', 't.id = r.team_id')
            ->join('races ra', 'ra.id = r.race_id')
            ->where('ra.season_year', $year)
            ->groupBy('t.id')
            ->orderBy('points', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        $seasonData[] = [
            'year'        => $year,
            'winner'      => $topDriver ? "{$topDriver->first_name} {$topDriver->last_name}" : '–',
            'team'        => $topTeam ? $topTeam->name : '–',
            'points'      => $topDriver->points ?? 0,
            'team_points' => $topTeam->points ?? 0,
        ];
    }

    return view('seasons/season', ['seasonData' => $seasonData]);
}


    public function show($year)
    {
        $season = $this->seasonModel->where('year', $year)->first();

        if (!$season) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Sezóna $year nenalezena");
        }

    $builder = $this->resultModel->builder('results r');
    $builder->select('
        d.id,
        d.first_name,
        d.last_name,
        t.name AS team_name,
        SUM(r.points) AS total_points,
        SUM(CASE WHEN r.position = 1 THEN 1 ELSE 0 END) AS wins
    ');
    $builder->join('drivers d', 'd.id = r.driver_id');
    $builder->join('teams t', 't.id = r.team_id');
    $builder->join('races ra', 'ra.id = r.race_id');
    $builder->where('ra.season_year', $year);
    $builder->groupBy('d.id, d.first_name, d.last_name, t.name');
    $builder->orderBy('total_points', 'DESC');

    $results = $builder->get()->getResultArray();

    // Výsledky týmů
    $teamBuilder = $this->resultModel->builder('results r');
    $teamBuilder->select('
        t.id,
        t.name,
        SUM(r.points) AS total_points,
        SUM(CASE WHEN r.position = 1 THEN 1 ELSE 0 END) AS wins
    ');
    $teamBuilder->join('teams t', 't.id = r.team_id');
    $teamBuilder->join('races ra', 'ra.id = r.race_id');
    $teamBuilder->where('ra.season_year', $year);
    $teamBuilder->groupBy('t.id, t.name');
    $teamBuilder->orderBy('total_points', 'DESC');

    $teamResults = $teamBuilder->get()->getResultArray();

    $seasonModel = new \App\Models\SeasonModel();
    $season = $seasonModel->where('year', $year)->first();

    foreach ($results as &$row) {
    $row['total_points'] = (int)$row['total_points'];
}
foreach ($teamResults as &$teamRow) {
    $teamRow['total_points'] = (int)$teamRow['total_points'];
}


    return view('seasons/season_details', [
        'year' => $year,
        'season' => $season,
        'results' => $results,
        'teamResults' => $teamResults,
    ]);
    }
}
