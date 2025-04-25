<?php

namespace App\Controllers;

use App\Models\DriverModel;
use CodeIgniter\Controller;

class DriverController extends Controller
{
    public function index()
    {
        $model = new DriverModel();
        $data['drivers'] = $model->orderBy('points', 'DESC')->findAll();

        return view('drivers/driver', $data);
    }

    public function create()
    {
        return view('drivers/create');
    }

    public function store()
    {
        helper('text');
        $model = new DriverModel();

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
        $model = new DriverModel();
        $data['driver'] = $model->find($id);

        return view('drivers/edit', $data);
    }

    public function update($id)
    {
        helper('text');
        $model = new DriverModel();

        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');

        $image = strtolower($firstName . '_' . $lastName);
        $image = iconv('UTF-8', 'ASCII//TRANSLIT', $image);
        $image = preg_replace('/[^a-z0-9]+/', '_', $image);
        $image = trim($image, '_') . '.png';

        $model->update($id, [
            'first_name'  => $firstName,
            'last_name'   => $lastName,
            'nationality' => $this->request->getPost('nationality'),
            'dob'         => $this->request->getPost('dob'),
            'image'       => $image,
        ]);

        return redirect()->to('/drivers');
    }

    // Soft delete
    public function delete($id)
{
    $model = new DriverModel();

    // Použijeme soft delete místo trvalého smazání
    $driver = $model->find($id);

    if ($driver) {
        $model->delete($id); // Soft delete, nastaví `deleted_at` na aktuální datum a čas
        return redirect()->to('/drivers');
    } else {
        // Pokud není řidič nalezen
        return redirect()->to('/drivers')->with('error', 'Řidič nenalezen.');
    }
}

    // Zobrazit smazané (soft deleted)
    public function trashed()
    {
        $model = new DriverModel();
        $data['drivers'] = $model->onlyDeleted()->orderBy('points', 'DESC')->findAll();

        return view('drivers/trashed', $data);
    }

    // Obnovit smazaného řidiče
    public function restore($id)
{
    $model = new DriverModel();

    // Najdeme smazaného řidiče
    $driver = $model->onlyDeleted()->find($id);

    if ($driver) {
        // Obnovíme řidiče tím, že ručně nastavíme `deleted_at` na NULL
        $builder = $model->builder();
        $builder->where('id', $id);
        $builder->update(['deleted_at' => null]);  // Nastavíme `deleted_at` na null

        return redirect()->to('/drivers/trashed');
    } else {
        // Pokud řidič neexistuje nebo není smazaný
        return redirect()->to('/drivers/trashed')->with('error', 'Tento řidič byl trvale smazán nebo neexistuje.');
    }
}

    // Trvale smazat (hard delete)
    public function forceDelete($id)
    {
        $model = new DriverModel();
        $model->delete($id, true); // true = permanent delete

        return redirect()->to('/drivers/trashed');
    }
}