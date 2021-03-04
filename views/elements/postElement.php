<div class="card col-12 mt-4 mb-2">
    <div class="card-header">
        <?= 'pseudo : ' . $html->button($post->User->pseudo,['dir' => 'posts', 'page' => 'profil', 'option' => ['id' => $post->user_id]]) . ' crée le : ' . $post->created; ?>
    </div>
    <div class="card-body">
        <h5 class="card-title"><?= $post->title ?></h5>
        <p class="card-text"><?= $post->content ?></p>
        <p><span class="badge bg-secondary">
        <?= 'jeu : ' . $post->Game->name . ' ' . $post->id; ?>
        </span></p>
        <?= $html->button('Voir le Post',['dir' => 'posts', 'page' => 'post', 'option' => ['id' => $post->id]]) ?>
        <?php
            $likes = new Likes($post->id);
            if ($likes->isAlreadyLike($post->id, $Auth->user->id)) {
                $form = new BootstrapForm('unlike', 'Posts', METHOD_POST);
                $form->addInput('post_id', TYPE_HIDDEN, ['value' => $post->id]);
                $form->addInput('link', TYPE_HIDDEN, ['value' => 'profil?id=' . $_GET['id']]);
                $form->setSubmit('Unlike', ['color' => SUCESS]);
                echo $form->form();
            } else {
                $form = new BootstrapForm('new_likes', 'Posts', METHOD_POST);
                $form->addInput('post_id', TYPE_HIDDEN, ['value' => $post->id]);
                $form->addInput('link', TYPE_HIDDEN, ['value' => 'profil?id=' . $_GET['id']]);
                $form->setSubmit('Likes', ['color' => SUCESS]);
                echo $form->form();
            }
        ?>
        <div class="d-inline">
            <?= $likes->getLikes($post->id) ?>
        </div>
    </div>
    <div class='commentaire'>
        <h3>Commentaire(s) : </h3>
        <?php
            $commentaire = new Commentaire();
            $listOfCommentaire = $commentaire->listOfCommentaire($post->id, 2);
        ?>
        <?php foreach($listOfCommentaire as $commentaire): ?>
        <div class="col-8 mt-4 mb-2">
            <?php echo '<p>' . $html->button($commentaire->User->pseudo,
                            ['dir' => 'posts',
                            'page' => 'profil',
                            'option' => ['id' => $commentaire->User->id]
                    ],['color' => WARNING]) . ' : ' . $commentaire->content . '</p>'; ?>
        </div>
        <?php endforeach;?>
    </div>
</div>
