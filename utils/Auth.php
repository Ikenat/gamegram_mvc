<?php
class Auth
{
    public $user;
    public $logged = false;

    public function __construct()
    {
        if (isset($_SESSION[SESSION_USER_ID])) {
            $this->setUser($_SESSION[SESSION_USER_ID]);
        }
    }

    public function setUser($idUser)
    {
        $this->user = new User($idUser);
        $this->logged = true;
        $_SESSION[SESSION_USER_ID] = $idUser;
    }

    public function logout()
    {
        $this->logged = false;
        unset($_SESSION[SESSION_USER_ID]);
        unset($this->user);
    }
}