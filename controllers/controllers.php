<?php 

    include('loader.php');

    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    if(isset($_POST['nouvel_inscription'])) {
        //Je vais traiter mon formulaire d'inscription
        // Inscrire mon utilisateur ?
        // [0] Contrôles de base :
            // Nettoyage des données, avec la fonction htmlentities
            $validator = new Validator($_POST, 'inscription.php');

            // username est bien une adresse mail, avec la fonction filter_var
            $validator->validateEmail('username');

            $validator->validateLength('password', 8);

            // pseudo fait bien 4 caractères minimum, strlen
            $validator->validateLength('pseudo', 4);

            // nb_jeux est bien un nombre, is_numeric
            $validator->validateNumeric('nb_jeux');

        // [1] Contrôles d'unicité (via les modèles et l'ORM)
            // username est unique
            $validator->validatorUnique('username', 'users.username');

            // pseudo est unique
            $validator->validatorUnique('pseudo', 'users.pseudo');

        // [2] Contrôle Qualité du mot de passe
            // mot de passe fait bien 8 caractères minimum, strlen
            $validator->validatePassword('password', 8);

        // Tous mes contrôles sont OK, je peux ajouter mon nouvel utilisateur
            // Crypte le mot de passe, via md5
            $validator->hash('password');
            
            // Inserer le tout dans la table grâce à mon ORM
            $data = $validator->getData();

            $user = new User();
            $user->create(
                $data['username'],
                $data['password'],
                $data['pseudo'],
                $data['nb_jeux']
            );

            $Alert->setAlert('Compte créé avec succès !', ['color' => SUCESS]);
            $Alert->redirect('connexion.php');
    }

    if (isset($_POST['connexion'])) {
        //Je veux savoir si mon couple + mot de passe correspond bien à User enregirsté ?
        $validator= new Validator($_POST, 'inscription.php');

        $validator->validateEmail('email');
        $validator->validatePassword('password', 8);
        $validator->hash('password');
        $data = $validator->getData();

        //je cherche un user qui correspond au couple login
        $user = new User();
        if (!$user->login($data['email'], $data['password'])) {
            $alert->setAlert('Mauvaise combinaison login/Mot de Passe', ['color' => DANGER]);
            $alert->redirect('connexion.php');
        }

        //user à été populate par la méthode login

        //Je vais mettre en SESSION le fait que je me suis connecté
        //je vais le faire via un objet dédié : Auth
        $Auth->setUser($user->id);
        $alert->setAlert('Welcome Back ' . $Auth->user->pseudo, ['color' => SUCESS]);
        $alert->redirect('feed.php');


        if(isset($_GET['action']) && $_GET['action'] == 'logout') {
            $Auth->logout();

            $alert->setAlert('Déconnexion OK', ['color' => SUCESS]);
            $alert->redirect('index.php');
        }
    }

    // Déconnexion
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $Auth->logout();

    $alert->setAlert('Déconnexion OK', ['color' => SUCESS]);
    $alert->redirect('index.php');
}

if (isset($_POST['nouveau_post'])) {
    $validator= new Validator($_POST, 'newPost.php');

    //title n'est pas vide
    $validator->validateLength('title', 10);
    //le contenu n'est pas vide
    $validator->validateLength('content', 30);
    ///un jeu a bien été choisi
    $validator->validateNumeric('game_id');

    $data = $validator->getData();

    //Accès direct ici au User.id
    $post = new Post();
    $post->create(
        $Auth->user->id,
        $data['game_id'],
        $data['title'],
        $data['content']
    );

    $alert->setAlert('Post crée avec succès', ['color' => SUCESS]);
    $alert->redirect('feed.php');
}

if (isset($_POST['nouveau_commentaire'])) {
    $validator= new Validator($_POST, 'post.php');

    //verifie que mon commentaire n'est pas vide
    //title n'est pas vide
    $validator->validateLength('content', 10);

    $data = $validator->getData();

    $commentaire = new Commentaire();
    $commentaire->create(
        $data['post_id'],
        $Auth->user->id,
        $data['content'],
    );

    $alert->setAlert('commentaire crée avec succès', ['color' => SUCESS]);
    $alert->redirect('post.php?id=' . $data['post_id']);
}

if (isset($_POST['new_likes'])) {
    $validator= new Validator($_POST, 'feed.php');


    $data = $validator->getData();

    $likes = new Likes();
    $likes->create(
        $data['post_id'],
        $Auth->user->id,
    );
    if (isset($data['link'])) {
        $alert->redirect($data['link']);
    }
    $alert->redirect($_SERVER['HTTP_REFERER'] . '#post_' . $data['post_id']);
    //$alert->redirect('feed.php');
}

if (isset($_POST['unlike'])) {
    $validator= new Validator($_POST, 'feed.php');


    $data = $validator->getData();

    $likes = new Likes();
    $likes->deleteLike(
        $data['post_id'],
        $Auth->user->id,
    );
    $alert->redirect($_SERVER['HTTP_REFERER'] . '#post_' . $data['post_id']);
    //$alert->redirect('feed.php');
}



if (isset($_POST['update_data'])) {
    //Inscrire mon users
            $updatePassword = true;
            if (isset($_POST['password'])) {
                unset($_POST['password']);
                $updatePassword = false;
            }
            //[0] Controle de base
                //nettoyage de donnée, avec la méhode => htmlentities
                $validator = new Validator($_POST, 'edit_profil.php');
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
                if ($data['username'] != $Auth->user->username) {
                    $validator->validatorUnique('username', 'users.username');
                }

                //vérifie si le pseudo existe déjà
                if ($data['pseudo'] != $Auth->user->pseudo) {
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

                $Auth->user->updateData($toUpdate, $Auth->user->id);

                $alert->setAlert('Compte modifié avec succès');
                $alert->redirect('profil.php');

}

