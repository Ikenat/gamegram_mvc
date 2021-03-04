<?php

class PostsProcessController extends Controller
{
    public $User;
    public function __construct()
    {
        parent::__construct();
        $this->User = new User;
    }

    public function newLikes()
    {
        $validator= new Validator($_POST, 'feed.php');


        $data = $validator->getData();

        $likes = new Likes();
        $likes->create(
        $data['post_id'],
        $this->Auth->user->id,
        );
        if (isset($data['link'])) {
            $this->Alert->redirect($data['link']);
        }
        $this->Alert->redirect(['url' => $_SERVER['HTTP_REFERER'] . '#post_' . $data['post_id']]);
    }

    public function unlike()
    {
        $validator= new Validator($_POST, 'feed.php');


        $data = $validator->getData();
        var_dump($data);
        $likes = new Likes();
        $likes->deleteLike(
            $data['post_id'],
            $this->Auth->user->id,
        );
        //$this->Alert->redirect(['url' => $_SERVER['HTTP_REFERER'] . '#post_' . $data['post_id']]);
    }
    public function NouveauPost()
    {
        $validator= new Validator($_POST, 'newPost.php');

        //title n'est pas vide
        $validator->validateLength('title', 10);
        //le contenu n'est pas vide
        $validator->validateLength('content', 30);
        ///un jeu a bien été choisi
        $validator->validateNumeric('game_id');

        $data = $validator->getData();

        //Accès direct ici au User.id
        $post = new Post();
        $post->create(
            $this->Auth->user->id,
            $data['game_id'],
            $data['title'],
            $data['content']
        );

        $this->Alert->setAlert('Post crée avec succès', ['color' => SUCESS]);
        $this->Alert->redirect(['dir' => 'posts', 'page' => 'feed']);
    }
}