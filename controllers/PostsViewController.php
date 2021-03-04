<?php

class postsViewController extends Controller
{
    public $Post;
    public function __construct()
    {
        parent::__construct();
        $this->Post = new Post;

        //$this->notAllowIfNotLogged();
    }

    public function feed()
    {

        return [
            'listOfPosts' => $this->Post->listOfPosts(20),
            'title' => 'Feed',
            'description' => 'Les Posts des internautes'
        ];
    }

    public function newPost()
    {
        return [
            'gamesById' => (new Game)->publicGameById(),
            'title' => 'create Post',
            'description' => 'Créer un Post'
        ];
    }
    public function post()
    {
        if (!$this->Post->exist()) {
            $this->Alert->setAlert('Ce post n\'existe pas');
            //$this->Alert->redirect(['dir' => 'posts', 'page' => 'feed']);
        }
        return [
            'post' => new Post($_GET['id']),
            'title' => 'create Post',
            'description' => 'Créer un Post'
        ];
    }

    public function profil()
    {
        $user = new User($_GET['id']);
        return [
            'user' => $user,
            'nbPost' => count($user->lastPosts()),
            'title' => 'create Post',
            'description' => 'Créer un Post'
        ];
    }
}