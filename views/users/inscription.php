
<div class="starter-template text-center py-5 px-3">
    <?=  $alert->displayAlert() ?>
    <h1>Inscription</h1>
    <p>Merci de bien vouloir remplir ce formulaire</p>
    <div class="row justify-content-center">
        <div class="col col-lg-4">
    <?php
    $form = new BootstrapForm('nouvel_inscription', 'Users', METHOD_POST);
    $form->addInput('username', TYPE_EMAIL, ['label' => 'AdresseEmail', 'placeholder' => 'mail@doamin.ext']);
    $form->addInput('password', TYPE_PASSWORD, ['label' => 'Password', 'placeholder' => '8 caractères minimum']);
    $form->addInput('pseudo', TYPE_TEXT, ['label' => 'Pseudo', 'placeholder' => 'Pseudo']);
    $form->addInput('nb_jeux', TYPE_NUMBER, ['label' => 'Nombre de jeux', 'placeholder' => 'Nombre de jeux', 'value' => 0, 'min' => 1, 'max' => 100, 'step' => 1]);

    $form->setSubmit('Je m\'inscris', ['color' => SUCESS]);

    echo $form->form();
    ?>
    <hr>
    <p>Déjà un Compte ?</p>
    <?= $html->button('Connexion', ['dir' => 'users', 'page' => 'Connexion'], ['color' => WARNING]) ?>
        </div>
    </div>
</div>
