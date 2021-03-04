<?php

require('loader.php');

$urls = [
    ['dir' => '', 'page' => '', 'options' => [], 'result' => 'index.php'],
    ['dir' => 'games', 'page' => 'jeux', 'options' => [], 'result' => 'index.php?dir=games&page=jeux'],
    ['dir' => 'games', 'page' => 'jeu', 'options' => ['id' => 3], 'result' => 'index.php?dir=games&page=jeu&id=3']
];

foreach ($urls as $url) {
    $result = Router::url(
        $url['dir'],
        $url['page'],
        $url['options']
    );
    echo '<p>' . $result .'</p>';

    if ($result == $url['result']) {
        echo '<p style="color:green;">Test OK</p>';
    } else {
        echo '<p style="color:red;">Test KO</p>';
    }
}

/*
//test manuels
$families = new Family();
//$family = $families->create('RPG');



$pass = [
    ['pass' => '', 'ok' =>false],
    ['pass' => 'aaa', 'ok' =>false],
    ['pass' => 'abc', 'ok' =>false],
    ['pass' => 'a3a', 'ok' =>false],
    ['pass' => 'a65', 'ok' =>false],
    ['pass' => 'a6+A', 'ok' =>false],
    ['pass' => '8*d', 'ok' =>false],
    ['pass' => '-erzE48644', 'ok' =>true],
    ['pass' => '48644zrIh(]', 'ok' =>true],
    ['pass' => '54619191819119', 'ok' =>false],
    ['pass' => '+-+-+-+-++--++-*', 'ok' =>false],
    ['pass' => '/J2d7-T92s', 'ok' =>true ],
    ['pass' => '12ふdうPふうふうふう--', 'ok' => true],
    ['pass' => '123-ééTéé-123', 'ok' => true]
];

foreach ($pass as $p) {
    $validator = new Validator($p, 'url');

    if($validator->validatePassword('pass', 8) === $p['ok']) {
        echo '<p style="color:green;">Test OK</p>';
    }else {
        echo '<p style="color:red;">Test KO</p>';
    }
}





echo '<br />';
//$orm->setSql('SELECT name FROM games');
$game = new Game(3);
echo 'Le Jeu présent à l\'id ' . $game->id .
' se nomme ' . $game->name .
' se joue sur ' . $game->Platform->name;
die();


echo 'Il y a ' . $orm->get('count') . ' jeux dans la base ';

//tout les element
/*
echo '<pre>';
print_r($orm->get('all'));
echo'<pre>';
*/

/*
//un élément
echo '<pre>';
print_r($orm->get('all'));
echo'<pre>';
*/

//autres requêtes que SELECT
/*
$orm = new ORM();
$orm->setSQL('UPDATE games SET score = 9 WHERE score >= 8.5');
$orm->lauch();

//temps 2
$orm->setTable('games');
$orm->setFields('name', 'score');
//A la place de $orm->setSQL('SELECT names FROM games');
*/
