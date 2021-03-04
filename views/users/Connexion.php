
<div class="starter-template text-center py-5 px-3">
    <?=  $alert->displayAlert() ?>
    <h1>Connexions</h1>
    <p>Welcome Back</p>
    <div class="row justify-content-center">
        <div class="col col-lg-4">
    <?php
    $form = new BootstrapForm('Connexion', 'Users', METHOD_POST);
    $form->addInput('username', TYPE_EMAIL, ['label' => 'AdresseEmail', 'placeholder' => 'mail@doamin.ext']);
    $form->addInput('password', TYPE_PASSWORD, ['label' => 'Password', 'placeholder' => '8 caractères minimum']);

    $form->setSubmit('Connexion', ['color' => SUCESS]);

    echo $form->form();
    ?>
    <hr>
    <p>Pas encore de compte ?</p>
    <?= $html->button('Inscription', ['dir' => 'users', 'page' => 'inscription'], ['color' => WARNING]) ?>
        </div>
    </div>
</div>
