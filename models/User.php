<?php
class User extends ORM
{

    public function __construct($id = null)
    {
        parent::__construct();
        $this->setTable('users');

        if ($id != null) {
            $this->populate($id);
        }
    }
    public function create($username, $password, $pseudo,$nb_jeux)
    {
        $this->addInsertFields('username', $username, PDO::PARAM_STR);
        $this->addInsertFields('password', $password, PDO::PARAM_STR);
        $this->addInsertFields('pseudo', $pseudo, PDO::PARAM_STR);
        $this->addInsertFields('nb_games', $nb_jeux, PDO::PARAM_INT);
        $this->addInsertFields('created', date('Y-m-d'), PDO::PARAM_STR);
        $newId = $this->insert();//PDO::exec
        $this->populate($newId);
    }

    public function login($username, $passwordHashed)
    {
        $this->addWhereFields('username', $username, '=', PDO::PARAM_STR);
        $this->addWhereFields('password', $passwordHashed, '=', PDO::PARAM_STR);
        $this->setSelectFields('id');
        $user = $this->get('first');
        if (!empty($user)) {
            $this->populate($user['id']);
            return true;
        }
        return false;
    }
    public function lastPosts()
    {
        $posts = new Post();
        $this->Posts = $posts->listOfLastUserPost($this->id);
        return $this->Posts;
    }
    public function lastComments()
    {
        $commentaires = new Commentaire();
        $this->Comments = $commentaires->listOfLastUserComment($this->id);
        return $this->Comments;
    }
    public function lastPostAndComments()
    {
        $this->lastPosts();
        $this->lastComments();

        $dataByCreated = [];

        foreach ($this->Posts as $post) {
            $dataByCreated [] = [
                'created' => $post->created,
                'data' => $post
            ];
        }

        foreach ($this->Comments as $comment) {
            $dataByCreated [] = [
                'created' => $comment->created,
                'data' => $comment
            ];
        }

        usort($dataByCreated, function ($a, $b) {
            if ($a['created'] == $b['created']) {
                return 0;
            }
            return ($a['created'] > $b['created']) ? -1 : 1;
        });

        return $dataByCreated;
    }

    public function updateData($datas, $id = null)
    {
        if ($id != null) {
            $this->addWhereFields('id', $id, '=', PDO::PARAM_STR);
        } else {
            $this->addWhereFields('id', $this->id, '=', PDO::PARAM_STR);
        }

        foreach ($datas as $data) {
            $this->addUpdateField($data['field'], $data['value']);
        }

        $newId = $this->update();
        $this->populate($newId);


    }

}