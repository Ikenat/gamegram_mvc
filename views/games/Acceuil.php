
<div class="starter-template text-center py-5 px-3">
    <h1><?= NAME_APP ?></h1>
    <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
</div>

    <?= $html->image('retro.jpg', ['alt' => 'photo de mario qui domine le monde']); ?>

    <p class="text-center py-5 px-3">
    <?= $html->button('Présentation', ['dir' => 'games', 'page' => 'presentation'], ['color' => INFO])?>
    <?= $html->button('Je crée un compte', ['dir' => 'users', 'page' => 'inscription'], ['color' => SUCESS])?>
    </p>
<?php

