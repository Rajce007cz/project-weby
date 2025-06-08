<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RaceModel;
use App\Models\ResultModel;
use App\Models\DriverModel;
use App\Models\TeamModel;

class ResultController extends Controller
{
    protected $db;
    protected $builder;

    public function __construct()
    {
        $this->db      = \Config\Database::connect();
        $this->builder = $this->db->table('races');
        helper('date');
    }

    /* -------------------------------------------------- *
     *  POMOCNÁ FUNKCE: přepočítá body + vítězství jezdců  *
     * -------------------------------------------------- */
    private function rebuildDriverStats(): void
    {
        $driverModel = new DriverModel();

        $stats = $this->db->table('results')
    ->select("
        driver_id,
        SUM(points) AS total_points,
        SUM(CASE WHEN position = 1 THEN 1 ELSE 0 END) AS wins
    ")
    ->groupBy('driver_id')
    ->get()
    ->getResultArray();

foreach ($stats as $s) {
    $driverModel->update($s['driver_id'], [
        'points' => $s['total_points'],
        'win'    => $s['wins'],  // tady je to 'win' místo 'wins'
    ]);
}
}

    /* ======================  ADD  ====================== */
   public function addRace()
{
    helper('form');

    $raceModel   = new RaceModel();
    $driverModel = new DriverModel();
    $teamModel   = new TeamModel();
    $resultModel = new ResultModel();

    /* ---- dropdown data ---- */
    // názvy závodů (beze změny)
    $raceNames = array_column(
        $this->db->table('races')
                 ->distinct()->select('name')
                 ->orderBy('name')
                 ->get()->getResultArray(),
        'name'
    );

    // ✨ nové: seznam států
    $raceCountries = array_column(
        $this->db->table('races')
                 ->distinct()->select('country')
                 ->orderBy('country')
                 ->get()->getResultArray(),
        'country'
    );

    // jezdci + týmy (beze změny)
    $drivers = $driverModel->orderBy('last_name')->findAll();
    $teams   = $teamModel->orderBy('name')->findAll();

    if ($this->request->getMethod() === 'post') {
    // Získání dat z formuláře
    $date       = $this->request->getPost('date');
    $country    = $this->request->getPost('country');
    $raceName   = $this->request->getPost('race_name');
    $positions  = $this->request->getPost('position');
    $drivers    = $this->request->getPost('driver_id');
    $teams      = $this->request->getPost('team_id');
    $points     = $this->request->getPost('points');

    // Validace základních údajů o závodě
    if (!$date || !$country || !$raceName) {
        return redirect()->back()->with('error', 'Fill all race details.')->withInput();
    }

    // Uložení závodu
    $raceId = $raceModel->insert([
        'date'        => $date,
        'country'     => $country,
        'name'        => $raceName,
        'season_year' => 2025,
    ], true); // true = return insert ID

    // Uložení výsledků (pozice 1–20)
    foreach ($positions as $i => $pos) {
        $driverId = $drivers[$i] ?? null;
        $teamId   = $teams[$i] ?? null;
        $pointVal = $points[$i] ?? 0;

        // Pokud není vyplněný jezdec, přeskočíme
        if (!$driverId) {
            continue;
        }

        $resultModel->insert([
            'race_id'   => $raceId,
            'position'  => $pos,
            'driver_id' => $driverId,
            'team_id'   => $teamId,
            'points'    => $pointVal ?: 0,
        ]);
    }

    return redirect()->to('/seasons/season25')->with('message', 'Race successfully added.');
}

    /* ---- zobrazení formuláře ---- */
    return view('/seasons/season25/add', [
        'raceNames'     => $raceNames,
        'raceCountries' => $raceCountries,   // << pošli do view
        'drivers'       => $drivers,
        'teams'         => $teams,
    ]);
}

    /* ====================  SEASON LIST  ==================== */
    public function manageSeason()
    {
        $raceModel = new RaceModel();

        // závody 2025
        $races = $raceModel->where('season_year', 2025)
                           ->orderBy('date', 'ASC')
                           ->findAll();

        // výsledky všech jezdců pro rychlý výpis
        $rows = $this->db->table('results r')
    ->select('r.race_id, r.position, r.points, d.first_name, d.last_name')
    ->join('drivers d', 'd.id = r.driver_id')
    ->join('races ra', 'ra.id = r.race_id')
    ->where('ra.season_year', 2025)
    ->orderBy('r.position')
    ->get()->getResultArray();

// přetypování points na integer, aby nebyly desetinné části
foreach ($rows as &$row) {
    $row['points'] = (int)$row['points'];
}

$byRace = [];
foreach ($rows as $r) {
    $byRace[$r['race_id']][] = $r;
}

        return view('seasons/season25/index', [
            'races'  => $races,
            'byRace' => $byRace,
        ]);
    }

    /* =====================  EDIT  ===================== */
    public function editRace2025($id = null)
    {
        helper('form');

        $raceModel   = new RaceModel();
        $driverModel = new DriverModel();
        $teamModel   = new TeamModel();
        $resultModel = new ResultModel();

        /* -- kontrola existence závodu -- */
        $race = $raceModel->find($id);
        if (!$race) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Závod nenalezen');
        }

        /* -- data pro formulář -- */
        $raceNames = array_column(
            $this->db->table('races')
                     ->distinct()->select('name')->orderBy('name')
                     ->get()->getResultArray(),
            'name'
        );
        $drivers = $driverModel->orderBy('last_name')->findAll();
        $teams   = $teamModel->orderBy('name')->findAll();
        $results = $resultModel->where('race_id', $id)
                               ->orderBy('position')->findAll();

        /* ---------- zpracování POST ---------- */
        if ($this->request->getMethod() === 'post') {

            // 1) update závodu
            $raceModel->update($id, [
                'name'    => $this->request->getPost('race_name'),
                'country' => $this->request->getPost('country'),
                'date'    => $this->request->getPost('date'),
            ]);

            // 2) smaž staré výsledky
            $resultModel->where('race_id', $id)->delete();

            // 3) vlož nové výsledky
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

            // 4) přepočítat statistiky jezdců
            $this->rebuildDriverStats();

            return redirect()->to('/seasons/season25')
                             ->with('message', 'Závod upraven');
        }

        /* ---------- zobrazení formuláře ---------- */
        return view('/seasons/season25/edit', [
            'race'      => $race,
            'results'   => $results,
            'raceNames' => $raceNames,
            'drivers'   => $drivers,
            'teams'     => $teams,
        ]);
    }

    /* ====================  DELETE  ==================== */
    public function deleteRace2025($id = null)
    {
        $raceModel   = new RaceModel();
        $resultModel = new ResultModel();

        if (!$id || !$raceModel->find($id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Závod nenalezen');
        }

        // smaž výsledky i závod
        $resultModel->where('race_id', $id)->delete();
        $raceModel->delete($id);

        // po smazání přepočítej statistiky
        $this->rebuildDriverStats();

        return redirect()->to('/seasons/season25')
                         ->with('message', 'Závod smazán');
    }
}