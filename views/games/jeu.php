<?php
//Affichage d'un seul jeu

?>
<div class="starter-template text-center py-5 px-3">
    <h1><?= $html->description ?></h1>
    <p class="card-text"><small class="text-muted">Sortie en <?= $_game->year ?> </small></p>
    <p><?= $_game->name ?> est un jeu <?= $_game->Family->name ?> éditer par <?= $_game->Publisher->name ?>, Actuellement jouable sur <?= $_game->Platform->name ?> ayant reçu la note de <?= $html->badge($_game->note) ?> / 10</p>
    <p>
    <?= $html->button(
        '&larr; Retour',
        ['dir' => 'games', 'page' => 'jeux'],
        ['color' => 'light']
    )

    ?>
    </p>
</div>
<?php

