<?php 

class Controller 
{
    public $Auth;
    public $Alert;

    public function __construct()
    {
        $this->Auth = new Auth;
        $this->Alert = new Alert;
    }

    public function notAllowIfLogged($paramsLink = ['dir' => 'posts', 'page' => 'feed'])
    {
        if ($this->Auth->logged) {
            $this->Alert->setAlert('Tu ne peux pas accèder à cette page, tu es déjà connecté !', ['color' => DANGER]);
            $this->Alert->redirect($paramsLink);
        }
    }

    public function notAllowIfNotLogged($paramsLink = ['dir' => 'users', 'page' => 'Connexion'])
    {
        if ($this->Auth->logged) {
            $this->Alert->setAlert('Tu ne peux pas accèder à cette page, tu n\'es pas connecté !', ['color' => DANGER]);
            $this->Alert->redirect($paramsLink);
        }
    }
}