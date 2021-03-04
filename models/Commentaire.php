<?php
class Commentaire extends ORM
{
    public $Post;
    public function __construct($id = null)
    {
        parent::__construct();
        $this->setTable('comments');

        if ($id != null) {
            $this->populate($id);
        }
    }
    public function create($postId, $userId, $content)
    {
        $this->addInsertFields('post_id', $postId, PDO::PARAM_STR);
        $this->addInsertFields('user_id', $userId, PDO::PARAM_STR);
        $this->addInsertFields('content', $content, PDO::PARAM_INT);
        $this->addInsertFields('created', date('Y-m-d'), PDO::PARAM_STR);

        $newId = $this->insert();//PDO::exec
        $this->populate($newId);
    }
    public function listOfCommentaire($id, $limit = null, $offset = null)
    {
        //ORM//
        parent::addWhereFields('post_id', $id);
        parent::addOrder('id');
        if (isset($limit)) {
            parent::setLimit($limit, $offset);
        }

        $commentaires = parent::get(('all'));

        $listOfCommentaires = [];

        foreach ($commentaires as $commentaire) {
            $listOfCommentaires [] = new Commentaire($commentaire->id);
        }

        return $listOfCommentaires;
    }

    public function listOfLastUserComment($id)
    {
        //ORM//
        parent::addWhereFields('user_id', $id);
        parent::addOrder('created', 'DESC');

        $commentaires = parent::get(('all'));

        $listComments = [];

        foreach ($commentaires as $commentaire) {
            $listComments [] = new Commentaire($commentaire->id);
        }

        return $listComments;
    }

    public function populate($id)
    {
        if (parent::populate($id)) {
            $this->Post = new Post($this->post_id);
            $this->User = new User($this->user_id);
        }
    }

}