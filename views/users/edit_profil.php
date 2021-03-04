
<div class="starter-template text-center py-5 px-3">
<h1>Modification d'information</h1>
<?=  $alert->displayAlert() ?>
<?php
    $form = new BootstrapForm('update_data', 'Users', METHOD_POST);
    $form->addInput('username', TYPE_EMAIL, ['label' => 'AdresseEmail', 'placeholder' => 'mail@doamin.ext', 'value' => $Auth->user->username]);
    $form->addInput('password', TYPE_PASSWORD, ['label' => 'Mot de passe', 'placeholder' => 'Laisser vide pour ne pas le changer']);
    $form->addInput('pseudo', TYPE_TEXT, ['label' => 'Pseudo', 'placeholder' => 'Pseudo', 'value' => $Auth->user->pseudo]);
    $form->addInput('nb_games', TYPE_NUMBER, ['label' => 'Nombre de jeux', 'placeholder' => 'Nombre de jeux', 'value' => $Auth->user->nb_games, 'min' => 1, 'max' => 100, 'step' => 1]);

    $form->setSubmit('Mettre à jour', ['color' => SUCESS]);

    echo $form->form();
    ?>
</div>