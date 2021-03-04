<?php
class Post extends ORM
{
    public $Game;
    public $User;

    public function __construct($id = null)
    {
        parent::__construct();
        $this->setTable('posts');

        if ($id != null) {
            $this->populate($id);
        }
    }
    public function create($userId, $gameId, $title,$content)
    {
        $this->addInsertFields('user_id', $userId, PDO::PARAM_STR);
        $this->addInsertFields('game_id', $gameId, PDO::PARAM_STR);
        $this->addInsertFields('title', $title, PDO::PARAM_STR);
        $this->addInsertFields('content', $content, PDO::PARAM_INT);
        $this->addInsertFields('nb_likes', 0, PDO::PARAM_INT);
        $this->addInsertFields('created', date('Y-m-d'), PDO::PARAM_STR);

        $newId = $this->insert();//PDO::exec
        $this->populate($newId);
    }
    public function listOfPosts($limit = null, $offset = null)
    {
        //ORM//
        //parent::addWhereFields('public', 1);
        parent::addOrder('id');
        if (isset($limit)) {
            parent::setLimit($limit, $offset);
        }

        $posts = parent::get(('all'));

        $listPosts = [];

        foreach ($posts as $post) {
            $listPosts [] = new Post($post->id);
        }

        return $listPosts;
    }
    public function listOfLastUserPost($id)
    {
        //ORM//
        parent::addWhereFields('user_id', $id);
        parent::addOrder('created', 'DESC');
        /*/
        if (isset($limit)) {
            parent::setLimit($limit, $offset);
        }
        */

        $posts = parent::get(('all'));

        $listPosts = [];

        foreach ($posts as $post) {
            $listPosts [] = new Post($post->id);
        }

        return $listPosts;
    }

    public function populate($id)
    {
        if (parent::populate($id)) {
            $this->User = new User($this->user_id);
            $this->Game = new Game($this->game_id);
        }
    }

}