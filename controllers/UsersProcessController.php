<?php

class UsersProcessController extends Controller
{
    public $User;
    public function __construct()
    {
        parent::__construct();
        $this->User = new User;
    }

    public function nouvelInscription()
    {
        //Inscrire mon users

            //[0] Controle de base
                //nettoyage de donnée, avec la méhode => htmlentities
                $validator = new Validator($_POST, ['dir' => 'users', 'page' => 'inscription']);
                //username est bien une addresse mail => filter_var
                $validator->validateEmail('username');

                //mot de passe fait bien 8 caractère minimum =><strlen
                $validator->validateLength('password', 8);

                //peudo fait bien 4 caractère minimum => strlen
                $validator->validateLength('pseudo', 4);

                //regarde que nb_jeux est un nombre
                $validator->validateNumeric('nb_jeux');


            //[1] controle d'unicité (via les modèles et l'ORM)
                //username est unique
                $validator->validatorUnique('username', 'users.username');
                //pseudo est unique
                $validator->validatorUnique('pseudo', 'users.pseudo');


            //[2] Controle qualité du mot de passe
                //présence d'une lettre
                //présence d'un chiffre
                //présence d'un caractère spéciale
                $validator->validatePassword('password', 8);

            //Tous mes controle sont OK, je peux ajouter mon nouvel utilisateur
                //hash le mot de passe
                $validator->hash('password');
                //inserer le tout dans la table grâce à mon ORM
                $data = $validator->getData();

                $this->User->create(
                    $data['email'],
                    $data['password'],
                    $data['pseudo'],
                    $data['nb_jeux']
                );

                $this->Alert->setAlert('Compte crée avec succès');
                $this->Alert->redirect(['dir' => 'users', 'page' => 'Connexion']);
    }

    public function Connexion()
    {
        // [0] Je nettoie les données en provenance de l'utilisateur
        $validator = new Validator($_POST, ['dir' => 'users', 'page' => 'Connexion']);
        $validator->validateEmail('username');
        $validator->validatePassword('password', 8);
        $validator->hash('password');
        $data = $validator->getData();

        // [1] Je cherche un user qui correspond au couple login / mot de passe
        if (!$this->User->login($data['username'], $data['password'])) {
            $this->Alert->setAlert('Mauvaise combinaison login / mot de passe', ['color' => DANGER]);
            $this->Alert->redirect(['dir' => 'users', 'page' => 'Connexion']);
        }

        // $user a été populate par la méthode login

        // Je vais mettre en SESSION le fait que je suis connecté
        // Je vais le faire via un objet dédié : Auth(entification)
        $this->Auth->setUser($this->User->id);

        $this->Alert->setAlert('Welcome back ' . $this->Auth->User->pseudo . ' !', ['color' => SUCESS]);
        $this->Alert->redirect(['dir' => 'games', 'page' => 'presentation']);
    }

    public function Deconnexion()
    {
            $this->Auth->logout();

            $this->Alert->setAlert('Déconnexion OK', ['color' => SUCESS]);
            $this->Alert->redirect(['dir' => '', 'page' => 'index.php']);
    }

    public function updateData()
    {
        //Inscrire mon users
        $updatePassword = true;
        if (isset($_POST['password'])) {
            unset($_POST['password']);
            $updatePassword = false;
        }
        //[0] Controle de base
            //nettoyage de donnée, avec la méhode => htmlentities
            $validator = new Validator($_POST, ['dir' => 'users', 'page' => 'edit_profil']);
            //username est bien une addresse mail
            //$validator->validateEmail('email');
            //mot de passe fait bien 8 caractère minimum =><strlen
            if ($updatePassword) {
                $validator->validateLength('password', 8);
                $validator->validatePassword('password', 8);
                //Tous mes controle sont OK, je peux ajouter mon nouvel utilisateur
                //hash le mot de passe
                $validator->hash('password');
            }
            //peudo fait bien 4 caractère minimum => strlen
            $validator->validateLength('pseudo', 4);

            //regarde que nb_jeux est un nombre
            $validator->validateNumeric('nb_games');

            //[2] Controle qualité du mot de passe
            //présence d'une lettre
            //présence d'un chiffre
            //présence d'un caractère spéciale


            //inserer le tout dans la table grâce à mon ORM
            $data = $validator->getData();

            echo '<pre>';
            print_r($data);
            echo '</pre>';
            //enleve le nom du form
            unset($data['update_data']);

            //vérifie si l'email existe déjà
            if ($data['username'] != $this->Auth->user->username) {
                $validator->validatorUnique('username', 'users.username');
            }

            //vérifie si le pseudo existe déjà
            if ($data['pseudo'] != $this->Auth->user->pseudo) {
                $validator->validatorUnique('pseudo', 'users.pseudo');
            }


            $toUpdate = [
                ['field' => 'username', 'value' => $data['username']],
                ['field' => 'pseudo', 'value' => $data['pseudo']],
                ['field' => 'nb_games', 'value' => $data['nb_games']]
            ];
            if ($updatePassword) {
                $toUpdate [] = [
                    'field' => 'password',
                    'value' => $data['password']
                ];
            }

            $this->Auth->user->updateData($toUpdate, $this->Auth->user->id);

            $this->Alert->setAlert('Compte modifié avec succès');
            $this->Alert->redirect(['dir' => 'posts', 'page' => 'profil', 'option' => ['id' => $this->Auth->user->id]]);
    }
}