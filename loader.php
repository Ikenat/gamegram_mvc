<?php
//Démarrage de la session poru utiliser $_SESSION
session_start();

//Constante
define ('DIR_CONSTANTES', 'constante' . DIRECTORY_SEPARATOR);
include(DIR_CONSTANTES . 'system.php');
include(DIR_CONSTANTES . 'Bootstrap.php');
include(DIR_CONSTANTES . 'session.php');

//autoload
function loader($class) {
    //endroit ou peut se trouver ma class
    $folders = [
        DIR_MODELS,
        DIR_CONTROLLERS,
        DIR_UTILS
    ];

    foreach ($folders as $folder) {
        $fileName = $folder . $class . '.php';
        //regarde si mon fichier existe
        if (file_exists($fileName)) {
            require($fileName);
            return true;
        }
    }

    return false;
}
spl_autoload_register('loader');



$alert = new Alert(); // disponible partout dans toutes mes pages.
$Auth = new Auth;
