<?php

namespace App\Controllers;

use App\Models\DriverModel;
use CodeIgniter\Controller;

class DriverController extends Controller
{
    var $model;
    var $cofig;
    public function __construct(){
        $this->model = new DriverModel();
        $this->config = new \Config\MyConfig();
    }
    public function index()
    {
        $cardNumber =  $this->config->itemsForPage;
        $data['drivers'] = $this->model->orderBy('points', 'DESC')->paginate($cardNumber);
        $data["pager"] = $this->model->pager;
        return view('drivers/driver', $data);
    }

    public function create()
    {
        return view('drivers/create');
    }

    public function store()
    {
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');

        $image = strtolower($firstName . '_' . $lastName);
        $image = iconv('UTF-8', 'ASCII//TRANSLIT', $image);
        $image = preg_replace('/[^a-z0-9]+/', '_', $image);
        $image = trim($image, '_') . '.png';

        $model->save([
            'first_name'  => $firstName,
            'last_name'   => $lastName,
            'nationality' => $this->request->getPost('nationality'),
            'dob'         => $this->request->getPost('dob'),
            'image'       => $image,
        ]);

        return redirect()->to('/drivers');
    }

    public function edit($id)
    {
        $data['driver'] = $this->model->find($id);

        return view('drivers/edit', $data);
    }

    public function update($id)
    {

        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');

        $image = strtolower($firstName . '_' . $lastName);
        $image = iconv('UTF-8', 'ASCII//TRANSLIT', $image);
        $image = preg_replace('/[^a-z0-9]+/', '_', $image);
        $image = trim($image, '_') . '.png';

        $this->model->update($id, [
            'first_name'  => $firstName,
            'last_name'   => $lastName,
            'nationality' => $this->request->getPost('nationality'),
            'dob'         => $this->request->getPost('dob'),
            'image'       => $image,
        ]);

        return redirect()->to('/drivers');
    }

    // Soft delete, ten neupravujte
    public function delete($id)
{
    $driver = $this->model->find($id);
    if ($driver) {
        $this->model->delete($id);
        return redirect()->to('/drivers');
    } else {
        return redirect()->to('/drivers')->with('error', 'Řidič nebyl nalezen.');
    }
}
    public function trashed()
    {
        $data['drivers'] = $this->model->onlyDeleted()->orderBy('points', 'DESC')->findAll();

        return view('drivers/trashed', $data);
    }

    public function restore($id)
{
    $driver = $this->model->onlyDeleted()->find($id);
    if ($driver) {
        $builder = $this->model->builder();
        $builder->where('id', $id);
        $builder->update(['deleted_at' => null]);

        return redirect()->to('/drivers/trashed');
    } else {
        return redirect()->to('/drivers/trashed')->with('error', 'Tento řidič byl trvale smazán.');
    }
}

    public function forceDelete($id)
    {
        $this->model->delete($id, true);
        return redirect()->to('/drivers/trashed');
    }
    
    public function show($id)
{
    $driverModel = new DriverModel();
    $driver = $driverModel->find($id);

    if (!$driver) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Jezdec nenalezen.");
    }

    $db = \Config\Database::connect();

    // Výsledky sezón pro daného jezdce
    $builder = $db->table('results r');
    $builder->select('s.year, SUM(r.points) AS season_points, COUNT(CASE WHEN r.position = 1 THEN 1 END) AS season_wins');
    $builder->join('races ra', 'ra.id = r.race_id');
    $builder->join('seasons s', 's.year = ra.season_year');
    $builder->where('r.driver_id', $id);
    $builder->groupBy('s.year');
    $builder->orderBy('s.year', 'DESC');
    $seasonResults = $builder->get()->getResultArray();

    // Získání pořadí v každé sezóně
    foreach ($seasonResults as &$season) {
        $year = $season['year'];

        // Získáme všechny jezdce + jejich body v daném roce
        $rankingBuilder = $db->table('results r');
        $rankingBuilder->select('r.driver_id, SUM(r.points) AS total_points');
        $rankingBuilder->join('races ra', 'ra.id = r.race_id');
        $rankingBuilder->where('ra.season_year', $year);
        $rankingBuilder->groupBy('r.driver_id');
        $rankingBuilder->orderBy('total_points', 'DESC');

        $allDrivers = $rankingBuilder->get()->getResultArray();

        // Najdeme pozici tohoto jezdce
        $position = 1;
        foreach ($allDrivers as $d) {
            if ($d['driver_id'] == $id) {
                break;
            }
            $position++;
        }

        $season['season_position'] = $position; // přidáme do výstupu
    }

    return view('drivers/driver_details', [
        'driver'         => $driver,
        'seasonResults'  => $seasonResults
    ]);
}
}