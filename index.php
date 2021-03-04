<?php
include('loader.php');
//Ce nouveau fichier index n'est plus lié au contexte de mon projet
//C'est un morceau de mon framework
//On va toujours rester sur index.php

// Comment afficher acceuil.php ?
$dir = $_GET['dir'] ?? 'games';
$page = $_GET['page'] ?? 'acceuil';

//Appel dynamique de mon controllers
$controller = $dir . 'ViewController';
$method = $page;

//Sécu 1: controle si mon controller existe
Router::controlFIle(DIR_CONTROLLERS, $controller);


$call = new $controller();

//Sécu 2: controle si ma méthode existe + si elle est public
Router::controlMethod($controller, $method);


$data = $call->$method();

foreach ($data as $name => $value) {
    $var = '_' . $name;
    ${$var} = $value;
}

//Sécu 3: Controle si ma vue existe
Router::controlFIle(DIR_VIEWS . $dir . DIRECTORY_SEPARATOR, $page);

include(DIR_VIEWS . 'templates/front.php');
