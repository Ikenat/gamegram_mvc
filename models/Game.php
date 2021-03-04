<?php

class Game extends ORM
{

    public function __construct($id = null)
    {
        parent::__construct();
        $this->setTable('games');

        if ($id != null) {
            $this->populate($id);
        }
    }

        //méthode spécifique à ce modèle
    public function listOfPublicGames()
    {
        //ORM//
        parent::addWhereFields('public', 1);
        parent::addOrder('id');

        return parent::get(('all'));
    }

    public function publicGameById()
    {
        $games = $this->listOfPublicGames();
        $tabgames = [];
        foreach ($games as $game) {
            $tabgames [$game->id] = $game->name;
        }

        return $tabgames;
    }

    //méthode du coeur du système
    public function populate($id)
    {
        if (parent::populate($id)) {
            $this->Platform = new Platform($this->platform_id);
            $this->Publisher = new Publisher($this->publisher_id);
            $this->Family = new Family($this->family_id);
        }
    }

}

//$game->$Game->getById()