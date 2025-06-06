<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function processRegister()
    {
        $userModel = new UserModel();

        $data = $this->request->getPost();
        $userModel->save([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/login');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function processLogin()
    {
        $userModel = new UserModel();
        $data = $this->request->getPost();
        $user = $userModel->where('username', $data['username'])->first();

        if ($user && password_verify($data['password'], $user['password'])) {
            session()->set('user_id', $user['id']);
            return redirect()->to('/');
        }

        return redirect()->back()->with('error', 'Špatné přihlašovací údaje');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}