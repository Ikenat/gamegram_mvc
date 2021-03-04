<?php

//Menu
$html->addMenu('Présentation', Router::urlView('games', 'presentation'));

$html->addMenu('Jeux', Router::urlView('games', 'jeux'));
if ($Auth->logged) {
    $html->addMenu('Feed', Router::urlView('posts', 'feed'));
    $html->addMenu('Déconnexion',Router::urlProcess('users', 'Deconnexion'));
    $html->addMenu('profil', Router::urlView('posts', 'profil', ['id' => $Auth->user->id]));
    //$html->addMenu('profil', 'profil.php?id=' . $Auth->user->id);
} else {
    $html->addMenu('Inscription', Router::urlView('users', 'inscription'));
    $html->addMenu('Connexion', Router::urlView('users', 'connexion'));
}



