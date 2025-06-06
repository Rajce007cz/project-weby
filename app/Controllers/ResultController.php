<?php

namespace App\Controllers;

use App\Models\ResultModel;
use App\Models\SeasonModel;
use CodeIgniter\Controller;
use App\Models\RaceModel;
use App\Models\DriverModel;
use App\Models\TeamModel;

class ResultController extends Controller
{
    protected $db;
    protected $builder;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('races');
    }



public function addRace()
{
    helper(['form']);

    $raceModel   = new \App\Models\RaceModel();
    $driverModel = new \App\Models\DriverModel();
    $teamModel   = new \App\Models\TeamModel();
    $resultModel = new \App\Models\ResultModel();
    $db          = \Config\Database::connect();

    /* ---------- NÁZVY ZÁVODŮ Z DB ---------- */
    // a) máš tabulku circuits (id,name)
    $builder = $db->table('races');
$builder->distinct();
$builder->select('name');
$builder->orderBy('name');
$query = $builder->get();
$raceNames = array_column($query->getResultArray(), 'name');

    /* ---------- DROPDOWN DATA ---------- */
    $drivers = $driverModel->orderBy('last_name')->findAll();
    $teams   = $teamModel ->orderBy('name')->findAll();

    /* ---------- POST (Ukládání) ---------- */
    if ($this->request->getMethod() === 'post') {
        $raceId = $raceModel->insert([
            'name'        => $this->request->getPost('race_name'),
            'country'     => $this->request->getPost('country'),
            'date'        => $this->request->getPost('date'),
            'season_year' => 2025,
        ], true);

        $pos = $this->request->getPost('position');
        $drv = $this->request->getPost('driver_id');
        $tm  = $this->request->getPost('team_id');
        $pts = $this->request->getPost('points');

        for ($i = 0; $i < 20; $i++) {
            if ($drv[$i]) {
                $resultModel->insert([
                    'race_id'   => $raceId,
                    'driver_id' => $drv[$i],
                    'team_id'   => $tm[$i],
                    'position'  => $pos[$i],
                    'points'    => $pts[$i] ?? 0,
                ]);
            }
        }
        return redirect()->to('/seasons/season25')->with('message','Závod přidán');
    }

    return view('/seasons/season25/add', [
        'raceNames' => $raceNames,
        'drivers'   => $drivers,
        'teams'     => $teams,
    ]);
}

/* --------------------------------------------------------------------- */

public function manageSeason()
{
    $db         = \Config\Database::connect();
    $raceModel  = new \App\Models\RaceModel();
    $driverTbl  = $db->prefixTable('drivers');
    $resultTbl  = $db->prefixTable('results');
    $racesTbl   = $db->prefixTable('races');

    // závody 2025
    $races = $raceModel->where('season_year', 2025)
                       ->orderBy('date','ASC')
                       ->findAll();

    // výsledky všech jezdců v 2025 (pro rychlé pole)
    $rows = $db->table('results r')
               ->select('r.race_id, r.position, r.points, d.first_name, d.last_name')
               ->join('drivers d', 'd.id = r.driver_id')
               ->join('races ra', 'ra.id = r.race_id')
               ->where('ra.season_year', 2025)
               ->orderBy('r.position')
               ->get()
               ->getResultArray();

    /* --- uspořádání do pole $byRace[race_id] = array( row,row… ) --- */
    $byRace = [];
    foreach ($rows as $r) {
        $byRace[$r['race_id']][] = $r;
    }

    return view('seasons/season25/index', [
        'races'  => $races,
        'byRace' => $byRace,
    ]);
}

    public function editRace2025($id = null)
    {
    {
    helper(['form']);

    $raceModel   = new \App\Models\RaceModel();
    $driverModel = new \App\Models\DriverModel();
    $teamModel   = new \App\Models\TeamModel();
    $resultModel = new \App\Models\ResultModel();
    $db          = \Config\Database::connect();

    // Načti závod a výsledky
    $race = $raceModel->find($id);
    if (!$race) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Závod nenalezen');
    }

    $results = $resultModel->where('race_id', $id)->orderBy('position')->findAll();

    // Názvy závodů z DB (distinct)
    $builder = $db->table('races');
    $builder->distinct();
    $builder->select('name');
    $builder->orderBy('name');
    $query = $builder->get();
    $raceNames = array_column($query->getResultArray(), 'name');

    // Dropdown data
    $drivers = $driverModel->orderBy('last_name')->findAll();
    $teams   = $teamModel->orderBy('name')->findAll();

    if ($this->request->getMethod() === 'post') {
        // Update závod
        $raceModel->update($id, [
            'name'    => $this->request->getPost('race_name'),
            'country' => $this->request->getPost('country'),
            'date'    => $this->request->getPost('date'),
        ]);

        // Smaž staré výsledky
        $resultModel->where('race_id', $id)->delete();

        // Vlož nové výsledky
        $pos = $this->request->getPost('position');
        $drv = $this->request->getPost('driver_id');
        $tm  = $this->request->getPost('team_id');
        $pts = $this->request->getPost('points');

        for ($i = 0; $i < 20; $i++) {
            if (!empty($drv[$i])) {
                $resultModel->insert([
                    'race_id'   => $id,
                    'driver_id' => $drv[$i],
                    'team_id'   => $tm[$i],
                    'position'  => $pos[$i],
                    'points'    => $pts[$i] ?? 0,
                ]);
            }
        }

        return redirect()->to('/seasons/season25')->with('message', 'Závod upraven');
    }

    return view('/seasons/season25/edit', [
        'race'      => $race,
        'results'   => $results,
        'raceNames' => $raceNames,
        'drivers'   => $drivers,
        'teams'     => $teams,
    ]);
}
}


}