<?php 

class GamesViewController extends Controller
{
    public $Game;
    public function __construct()
    {
        parent::__construct();
        $this->Game = new Game;
    }

    public function acceuil()
    {
        return [
            'title' => NAME_APP,
            'description' => 'Un tout nouveau réseau social porté sur l\'univers du jeux vidéos.'
        ];
    }

    public function presentation()
    {
        return [
            'title' => 'Présentation',
            'description' => 'Présentation de ' . NAME_APP
        ];
    }

    public function jeux()
    {
        return [
            'listOfGames' => $this->Game->listOfPublicGames(),
            'title' => 'Jeux',
            'description' => 'Liste des jeux présent sur ' . NAME_APP
        ];
    }

    public function jeu()
    {

        //Existe ? // + la fonction de controle passé en param
        //Si on remplit pas les conditions, on die direct
        $this->Game->populate(
            Router::get('id', 'is_numeric')
        );
        if ($this->Game->exist()) {
            $this->Alert->setAlert('Ce jeu n\'existe pas');
            //$this->Alert->redirect(['dir' => 'games', 'page' => 'jeux']);
        }
        return [
            'game' => $this->Game,
            'title' => $this->Game->name,
            'description' => $this->Game->name
        ];
    }
}