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
    helper(['form']);

    $rules = [
        'username'         => 'required|min_length[3]|max_length[50]',
        'email'            => 'required|valid_email|is_unique[users.email]',
        'password'         => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]'
    ];

    if (!$this->validate($rules)) {
        return view('auth/register', [
            'validation' => $this->validator
        ]);
    }

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
        $user = $userModel->where('username', $data['usernameOrEmail'])->orWhere('email', $data['usernameOrEmail'])->first();

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