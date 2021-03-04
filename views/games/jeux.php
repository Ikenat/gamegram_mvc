<div class="starter-template text-center py-5 px-3 ">
    <h1>Liste des jeux</h1>
    <?=  $alert->displayAlert() ?>
    <div class="d-flex justify-content-around align-content-center flex-wrap">
    <?php
        //$sql = 'SELECT id, name, year, note FROM games WHERE public = 1 ORDER BY name ASC';
        //$orm->setSQL($sql);
        ?>
        <?php foreach($_listOfGames as $game): ?>
          <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
              <div class="col-md-4" style="background:grey; border-radius:2px;">
              </div>
              <div class="col-md-7">
                <div class="card-body">
                  <h5 class="card-title"><?= $game->name ?></h5>
                  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                  <p class="card-text"><small class="text-muted">Sortie en <?= $game->year ?></small></p><?= $html->button('Voir le jeu',['dir' => 'games', 'page' => 'jeu', 'option' => ['id' => $game->id]]) ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
</div>
