<?php

class Bootstrap
{
    public $titlePage;
    public $description;
    private $MenuButtons = [];
    private $search;
    public $alert = [];

    public function __construct($title, $description)
    {
        $this->titlePage = $title;
        $this->description = $description;
    }
    public function startDOM()
    {
        return '<!doctype html>
        <html lang="fr">
          <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>' . $this->titlePage . '</title>
            <meta name="description" content="' . $this->description . '">

            <!-- Bootstrap core CSS -->
        <link href="./' . DIR_ASSETS . DIR_CSS . 'Bootstrap.css" rel="stylesheet" crossorigin="anonymous">

            <!-- Custom styles for this template -->
            <link href="./' . DIR_ASSETS . DIR_CSS . 'select2.css" rel="stylesheet">
            <link href="./' . DIR_ASSETS . DIR_CSS . 'summernote.css" rel="stylesheet">
            <link href="./' . DIR_ASSETS . DIR_CSS . 'theme.css" rel="stylesheet">
          </head>
          <body>';
    }
    public function endDOM()
    {
        return '<!--Javascript-->
        <script src="./' . DIR_ASSETS . DIR_JS . 'Jquery.js" crossorigin="anonymous"></script>
        <script src="./' . DIR_ASSETS . DIR_JS . 'select2.js"></script>
        <script src="./' . DIR_ASSETS . DIR_JS . 'summernote.js"></script>
        <script src="./' . DIR_ASSETS . DIR_JS . 'Bootstrap.js"></script>

        <script src="./' . DIR_ASSETS . DIR_JS . 'main.js" ></script>

      </body>
    </html>';
    }
    public function startMain()
    {
        return '<main class="container">';
    }

    public function endMain()
    {
        return '</main><!-- /.container -->';
    }

    //forme le html de mon menu
    public function menu()
    {
        $menu = '<nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php">' . NAME_APP . '</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">';
        foreach ($this->MenuButtons as $button) {
            $menu .= '<li class="nav-item">
            <a class="nav-link" href="' . $button['lien'] . '">' . $button['titre'] . '</a>
          </li>';
        }
        if ($this->search) {
            $menu .= '</li>
            </ul>
            <form class="d-flex">
            <input class="form-control me-2" type="search" placeholder="rechercher un jeux ..." aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Recherche</button>
          </form>';
        }
        $menu .= '</div>
        </div>
      </nav>';

        return $menu;
    }

        //permet d'ajouter un bouton au menu
    public function addMenu($title, $link)
    {
      $this->MenuButtons [] = [
          'lien' => $link,
          'titre' => $title
      ];
    }

    //defini si la barre de recherche doit être présente
    public function setDisplayRecherche($booleen)
    {
        $this->search = $booleen;
    }

    //permet d'afficher une image responsive.
    public function image($link , $options = [] )
    {
        //Equivalent du if() ternaire aussi appele Null coalescent
        $alt = $options['alt'] ?? '';
        return '<img src="./' . DIR_ASSETS . DIR_IMG . $link .'" class="img-fluid" alt="' . $alt . '">';
    }


    public function button($title, $linkParams, $options = [])
    {
        $link = Router::urlView(
          $linkParams['dir'],
          $linkParams['page'],
          $linkParams['option'] ?? []
        );
        $color = $options['color'] ?? PRIMARY;
        return '<a class="'. BTN . ' ' . BTN .'-' . $color . '" href="' . $link . '" role="button">'. $title .'</a>';
    }

    public function badge($text, $options = [])
    {
      $color = $options['color'] ?? PRIMARY;
      $class = $options['class'] ?? '';
      return '<span class="'. BADGE . ' bg-' . $color . ' ' . $class . '">' . $text .'</span>';
    }

    public function alert($text, $options = [])
    {
      $alert = new BootstrapAlert($text, $options);
      return $alert->alert();
    }
}

