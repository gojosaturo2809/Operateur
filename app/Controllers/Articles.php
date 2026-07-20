<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\UserModel;

class Articles extends BaseController
{
    public function index()
    {
        $articleModel = new ArticleModel();
        $userModel    = new UserModel();

        $articles = $articleModel->findAll();

        foreach ($articles as &$a) {
            $auteur      = $userModel->find($a['utilisateur_id']);
            $a['auteur'] = $auteur['nom'] ?? 'Inconnu';
        }
        unset($a);

        return view('articles/index', ['articles' => $articles]);
    }

    public function create()
    {
        return view('articles/create');
    }

    public function store()
    {
        $articleModel = new ArticleModel();

        $data                   = $this->request->getPost();
        $data['utilisateur_id'] = session()->get('user_id') ?? 1;

        $articleModel->insert($data);

        return redirect()->to('/articles');
    }

    public function delete($id = null)
    {
        $articleModel = new ArticleModel();
        $articleModel->delete($id);

        return redirect()->to('/articles');
    }
}
