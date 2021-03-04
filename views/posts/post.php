
<div class="starter-template text-center py-5 px-3">
    <div class="card col-12 mt-4 mb-2">
        <div class="card-header">
            <?php 
                echo 'Le ' . $_post->created . ' par ' . $_post->User->pseudo;
            ?>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?= $_post->title ?></h5>
            <p class="card-text"><?= $_post->content ?></p>
            <p><span class="badge bg-secondary">
            <?php
                echo 'jeu : ' . $_post->Game->name;
            ?>
            </span></p>
        </div>
    </div>
    <hr>
    </div>
    <div class='commentaire'>
        <h3>Commentaire(s)</h3>
        <?php
            $commentaire = new Commentaire();
            $listOfCommentaire = $commentaire->listOfCommentaire($_post->id);
        ?>
        <?php foreach($listOfCommentaire as $commentaire): ?>
        <div class="card col-8 mt-4 mb-2">
            <div class="card-header">
                <?php 
                    echo '<p> Le ' . $commentaire->created . ' par ' . $commentaire->User->pseudo . '</p>';
                ?>
            </div>
            <div class="card-body">
                <p class="card-text"><?= $commentaire->content ?></p>

            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <hr>
    <div class="addCommentaire">
        <h3>Laisser un commentaire</h3>
    </div>
    <?php 
    
    $form = new BootstrapForm('nouveau_commentaire', 'controllers.php', METHOD_POST);
    $form->addInput('content', TYPE_TEXTAREA, [ 'label' => 'Ton commentaire' ,'placeholder' => 'Commentaire...']);
    $form->addInput('post_id', TYPE_HIDDEN, ['value' => $_post->id]);
    $form->addInput('game_id', TYPE_HIDDEN, ['value' => $_post->Game->id]);
    $form->setSubmit('Commenter', ['color' => SUCESS]);

    echo $form->form();
    
    ?>
<?php
echo $html->endMain();
echo $html->endDOM();
