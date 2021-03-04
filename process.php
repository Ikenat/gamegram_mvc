<?php

include('loader.php');
//Ce nouveau fichier process n'est pas lié au contexte de mon projet
//C'est un morceau de mon framework
//Ce sera l'atterissage des formulaire, là ou se lance process, les traintement (de données)

// dir et page (pas de process par défaut)
$dir = $_GET['dir'] ?? '';
$page = $_GET['page'] ?? '';

//Appel dynamique de mon controllers
$controller = $dir . 'ProcessController'; // UsersProcessController

//Sécu 1: controle si mon controller existe
Router::controlFIle(DIR_CONTROLLERS, $controller);


$call = new $controller();

//Sécu 2: controle si ma méthode existe + si elle est public
Router::controlMethod($controller, $page);


$data = $call->{$page}(); //inscription

