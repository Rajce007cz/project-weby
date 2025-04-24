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

    public function delete($id)
    {
        $model = new DriverModel();
        $model->delete($id);

        return redirect()->to('/drivers');
    }
}