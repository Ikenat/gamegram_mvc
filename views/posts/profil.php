<?php


//$listOfPosts = $posts->listOfUserPost($_GET['id']);
?>
<div class="row">
<div class="card col-4 mt-4 mb-2" style="width: 18rem;">
  <img src="./assets/img/icons/profil.png" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title"><?= $_user->pseudo ?></h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">nombre de jeux : <?= $_user->nb_games ?></li>
    <li class="list-group-item">Nombre de post(s) : <?= $_nbPost ?></li>
    <li class="list-group-item">Crée depuis : <?= $_user->created ?></li>
  </ul>
  <div class="card-body text-center">
    <a href= <?= '"' . Router::urlView('users', 'edit_profil') . '"' ?> .  class="card-link">modifié mes informations</a>
  </div>
</div>
<div class= "col-8">
<?php
    $listOfPostAndComment = $_user->lastPostAndComments();

    foreach ($listOfPostAndComment as $interaction) {
        if (is_a($interaction['data'], 'Post')) {
            $post = $interaction['data'];
            include(DIR_VIEWS . 'elements/postElement.php');
        }

        if(is_a($interaction['data'], 'Commentaire')) {
            $comment = $interaction['data'];
            include(DIR_VIEWS . 'elements/CommentaireElement.php');
        }
    }
?>

