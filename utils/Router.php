<?php

class Router
{
    public static function urlView($dir = '', $page = '', $options = [])
    {
        $link = 'index.php';
        if ($dir == null || $page == null) {
            return $link;
        }
        $link .= '?dir=' . $dir . '&page=' . $page;

        foreach ($options as $name => $value) {
            $link .= '&' . $name . '=' . $value;
        }
        return $link;
    }

    public static function urlProcess($dir, $page, $options = [])
    {
        $link = 'process.php';
        $link .= '?dir=' . $dir . '&page=' . $page;

        foreach ($options as $name => $value) {
            $link .= '&' . $name . '=' . $value;
        }
        return $link;
    }

    public static function controlFIle($dir, $file, $extension = 'php') {
        $path = $dir . $file . '.' . $extension;
        if (!file_exists($path)) {
            die('Erreur Rooting [001] : ' . $file . ' inexistant');
        }
    }

    public static function controlMethod($class, $method) {
        if (!is_callable([$class, $method])) {
            die('Erreur Rooting [002] : ' . $method . ' inexistante');
        }
    }

    public static function get($id, $fonction = '') {

        if (!isset($_GET[$id])) {
            die('Erreur Rooting [003] : l\'id ' . $id . 'n\'existe pas ');
        }

        if ($fonction !== '' && !$fonction($_GET[$id])) {
            die('Erreur Rooting [003] : Mauvais format de ' . $id);
        }

        return $_GET[$id];
    }
}