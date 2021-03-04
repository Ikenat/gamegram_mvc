<?php
class Likes extends ORM
{
    public $Post;
    public $User;
    public function __construct($id = null)
    {
        parent::__construct();
        $this->setTable('likes');

        if ($id != null) {
            $this->populate($id);
        }
    }
    public function create($postId, $userId)
    {
        $this->addInsertFields('post_id', $postId, PDO::PARAM_STR);
        $this->addInsertFields('user_id', $userId, PDO::PARAM_STR);

        $newId = $this->insert();//PDO::exec
        $this->populate($newId);
    }
    public function deleteLike($post_id, $user_id)
    {
        parent::addWhereFields('post_id', $post_id);
        parent::addWhereFields('user_id', $user_id);

        parent::delete();
    }

    public function populate($id)
    {
        if (parent::populate($id)) {
            $this->Post = new Post($this->post_id);
            $this->User = new User($this->user_id);
        }
    }

    public function getLikes($id)
    {
        parent::addWhereFields('post_id', $id);
        $nblikes = parent::get(('count'));

        return $nblikes;
    }

    public function isAlreadyLike($post_id, $user_id)
    {
        parent::addWhereFields('post_id', $post_id);
        parent::addWhereFields('user_id', $user_id);

        if(parent::get('count') != 0) {
            return true;
        };

        return false;
    }
}