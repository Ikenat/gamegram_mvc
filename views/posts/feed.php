<div class="starter-template text-center py-5 px-3">
<h1>Dernier Post</h1>
    <?=  $alert->displayAlert() ?>
    <?= $html->button('Ajouter un post', ['dir' => 'posts', 'page' => 'newPost'], ['color' => INFO])?>
    <div class="d-flex justify-content-around align-content-center flex-wrap">
        <?php foreach($_listOfPosts as $post): ?>
            <div class="card col-12 mt-4 mb-2" id=<?= "'post_" . $post->id . "'" ?>>
                <div class="card-header">
                <?php
                echo 'pseudo : ' . $html->button($post->User->pseudo,
                ['dir' => 'posts',
                'page' => 'profil',
                'option' => ['id' => $post->User->id]
                ]);
                ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= $post->title ?></h5>
                    <p class="card-text"><?= $post->content ?></p>
                    <p><span class="badge bg-secondary">
                    <?php
                        //$game = new Game($post->game_id);
                        echo 'jeu : ' . $post->Game->name . ' ' . $post->id;
                    ?>
                    </span></p>
                    <?= $html->button('Voir le Post',
                    ['dir' => 'posts',
                    'page' => 'post',
                    'option' => ['id' =>$post->id ]
                    ]) ?>
                    <?php
                        $likes = new Likes($post->id);
                        if ($likes->isAlreadyLike($post->id, $Auth->user->id)) {
                            $form = new BootstrapForm('unlike', 'Posts', METHOD_POST);
                            $form->addInput('post_id', TYPE_HIDDEN, ['value' => $post->id]);
                            $form->setSubmit('Unlike', ['color' => SUCESS]);
                            echo $form->form();
                        } else {
                            $form = new BootstrapForm('new_likes', 'Posts', METHOD_POST);
                            $form->addInput('post_id', TYPE_HIDDEN, ['value' => $post->id]);
                            $form->setSubmit('Likes', ['color' => SUCESS]);
                            echo $form->form();
                        }
                        echo $likes->getLikes($post->id);
                    ?>
                </div>
                <div class='commentaire'>
                    <h3>Commentaire(s) : </h3>
                    <?php
                        $commentaire = new Commentaire();
                        $listOfCommentaire = $commentaire->listOfCommentaire($post->id, 2);
                    ?>
                    <?php foreach($listOfCommentaire as $commentaire): ?>
                    <div class="col-8 mt-4 mb-2">
                            <?php
                            echo '<p>' . $html->button($commentaire->User->pseudo,
                            ['dir' => 'posts',
                            'page' => 'profil',
                            'option' => ['id' => $commentaire->User->id]
                    ],['color' => WARNING]) . ' : ' . $commentaire->content . '</p>';
                            ?>
                    </div>
        <?php endforeach; ?>
    </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
