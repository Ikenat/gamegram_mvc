
<div class="starter-template text-center py-5 px-3">
    <?=  $alert->displayAlert() ?>
    <h1>Nouveau Post</h1>
    <div class="row justify-content-center">
        <div class="col-sm-4 col-lg-8">
        <?php
        $form = new BootstrapForm('nouveau_Post', 'Posts', METHOD_POST);
        $form->addInput('title', TYPE_TEXT, ['label' => 'Title du post']);
        $form->addInput('game_id', TYPE_SELECT, ['label' => 'Jeu en lien avec le Post', 'data' => $_gamesById, 'class' => 'select2']);
        $form->addInput('content', TYPE_TEXTAREA, ['label' => 'Content','rows' => 3]);

        $form->setSubmit('publié le post', ['color' => SUCESS]);

        echo $form->form();
        ?>
        </div>
    </div>
</div>

<?php
