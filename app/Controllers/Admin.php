<?php

namespace App\Controllers;

use App\Models\UserModel;

class Admin extends BaseController
{
    public function dashboard()
    {
        $userModel = new UserModel();
        $users     = $userModel->findAll();

        return view('admin/dashboard', ['users' => $users]);
    }

    public function deleteUser($id = null)
    {
        $userModel = new UserModel();
        $userModel->delete($id);

        return redirect()->to('/admin');
    }
}
