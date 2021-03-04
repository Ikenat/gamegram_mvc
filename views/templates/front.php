<?php

//début du DOM html
$html = new Bootstrap($_title, $_description);

echo $html->startDOM();


include(DIR_VIEWS . 'elements/menu.php');

echo $html->menu();


//Main

echo $html->startMain();


//MA PAGE
include(DIR_VIEWS . $dir . DIRECTORY_SEPARATOR . $page . '.php');


echo $html->endMain();
echo $html->endDOM();
