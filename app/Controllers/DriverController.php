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
        helper('text');
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
        helper('text');

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
        return redirect()->to('/drivers/trashed')->with('error', 'Tento řidič byl trvale smazán nebo neexistuje.');
    }
}

    public function forceDelete($id)
    {
        $this->model->delete($id, true);
        return redirect()->to('/drivers/trashed');
    }
}