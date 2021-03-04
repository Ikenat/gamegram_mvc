<?php

class Validator
{
    private $redirectParams;
    private $datas;
    private $typeProcess;

    private $Alert;
    private $Orm;


    public function __construct($datas, $redirectParams, $typeProcess = PROCESS_FORM)
    {
        $this->typeProcess = $typeProcess;
        foreach ($datas as $key => $value) {
            //je nettoie ma donnée
            $cleanValue = htmlentities($value);

            //regarde si je suis dans un formulaire
            if($this->typeProcess == PROCESS_FORM) {
                //Je vais mettre ma donnée en session
                $_SESSION[PROCESS_FORM_SESSION . $key] = $cleanValue;
            }

            //je stocke ma donnée nettoyé dans mon objet
            $this->datas[$key] = $cleanValue;
        }
        $this->redirectParams = $redirectParams;

        //instanciation autres objets
        $this->Alert = new Alert;
        $this->Orm = new Orm;
    }

    public function validateEmail($email)
    {
        var_dump($this->datas);
        if (!isset($this->datas[$email])) {
            die('Erreur [Val 001] Champ inconnu');
        }

        if (!filter_var($this->datas[$email], FILTER_VALIDATE_EMAIL)) {
            $this->redirectError('L\'email n\'est pas bon');
            die;
        }
    }

    public function validateLength($text, $length)
    {
        if (!isset($this->datas[$text])) {
            die('Erreur [Val 002] Champ inconnu');
        }

        if (strlen($this->datas[$text]) < $length) {
            $this->Alert->redirect($this->redirectParams);
            $this->redirectError('Le ' . $text . ' fait moins de ' . $length . ' caractères');
            die();
        }
    }

    public function validateNumeric($nb_jeux)
    {
        if (!isset($this->datas[$nb_jeux])) {
            die(' Erreur [Val 003] Champ inconnu');
        }

        if (!is_numeric($this->datas[$nb_jeux])) {
            $this->redirectError('Le nombre de jeux n\'est pas un nombre');
            die();
        }
    }

    public function validatorUnique($field, $tableAndField, $typePDO = PDO::PARAM_STR)
    {
        if (!isset($this->datas[$field])) {
            die('Erreur [Val 004] Champ inconnu');
        }

        [$table, $tableField] = explode('.', $tableAndField);
        $this->Orm->setTable($table);
        $this->Orm->addWhereFields($tableField, $this->datas[$field], '=', $typePDO);

        //reagarde s'il existe déjà un utilisateur
        if ($this->Orm->get('count') != 0) {
            $this->redirectError($field . ' existe déjà');
            die();
        }
    }

    public function validateSamePassword($password, $password2)
    {
        if ($this->datas[$password] !== $this->datas[$password2]) {
            die(' Erreur [Val 005] Les mots de passe ne sont pas similaire');
        }
    }
    public function validatePassword($field, $lenghMin)
    {
        if (!isset($this->datas[$field])) {
            die('Erreur [Val 006] Champ inconnu');
        }

        //Si présence d'une lettre en majuscule
        $uppercase = preg_match('@[A-Z]@', $this->datas[$field]);

        //Si présence d'un lettre en minuscule
        $lowercase = preg_match('@[a-z]@', $this->datas[$field]);

        //si présence d'un chiffre
        $number    = preg_match('@[0-9]@', $this->datas[$field]);

        //si présence d'un Metacharacter
        $speciaux  = preg_match('@[\W]@', $this->datas[$field]);
        $lengthPassword = strlen($this->datas[$field]);

        if (!$uppercase || !$lowercase || !$number || !$speciaux || $lengthPassword < $lenghMin) {
            $this->redirectError('Format mot de passe invalide');
            die;
        }
    }

    public function hash($field)
    {
        $this->datas[$field] = md5($this->datas[$field]);
    }

    private function redirectError($msg)
    {
        $this->Alert->setAlert($msg);
        $this->Alert->redirect($this->redirectParams);
    }

    public function getData()
    {
        return $this->datas;
    }

}