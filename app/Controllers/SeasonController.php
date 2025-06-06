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

        return view('seasons/season', ['seasons' => $seasons]);
    }

    public function show($year)
    {
        $season = $this->seasonModel->where('year', $year)->first();

        if (!$season) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Sezóna $year nenalezena");
        }

        /* Dotaz na výsledky jezdců v dané sezóně
        $builder = $this->db->table('results');//, SUM(results.points) as total_points, SUM(results.position = 1) as wins
        $builder->select('drivers.id, drivers.first_name, drivers.last_name, teams.name as team_name');
        $builder->join('drivers', 'drivers.id = results.driver_id');
        $builder->join('teams', 'teams.id = results.team_id');
        $builder->join('races', 'races.id = results.race_id');
        $builder->where('races.season_year', $year);
        $builder->groupBy('drivers.id');
        $builder->orderBy('total_points', 'DESC');

        $drivers = $builder->get()->getResultArray();

        return view('seasons/season_details', [
            'season' => $season,
            'drivers' => $drivers,
        ]);*/
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

    return view('seasons/season_details', [
        'year' => $year,
        'season' => $season,
        'results' => $results,
        'teamResults' => $teamResults,
    ]);
    }
}
