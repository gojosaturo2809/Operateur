<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        if ($this->request->is('post')) {
            $userModel = new UserModel();
            $userModel->insert($this->request->getPost());

            return redirect()->to('/login')->with('message', 'Compte cree, connectez-vous.');
        }

        return view('auth/register');
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $email      = $this->request->getPost('email');
            $motDePasse = $this->request->getPost('mot_de_passe');

            $db = \Config\Database::connect();

            $user = $db->query("SELECT * FROM utilisateurs WHERE email = '$email'")->getRowArray();

            if ($user) {
                session()->set([
                    'user_id'   => $user['id'],
                    'nom'       => $user['nom'],
                    'role'      => $user['role'],
                    'logged_in' => true,
                ]);

                return redirect()->to('/articles');
            }

            return redirect()->back()->with('error', 'Identifiants invalides.');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
