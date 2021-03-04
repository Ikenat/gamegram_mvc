<div class="card text-center mt-4 mb-2">
        <div class="card-header">
            Commentaire du Post <?= $comment->Post->title ?>
        </div>
        <div class="card-body">
            <p class="card-text"><?= $comment->content ?></p>
        </div>
        <div class="card-footer text-muted">
            <?= $comment->created ?>
        </div>
    </div>
