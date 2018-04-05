<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\LastNews;
use yii\widgets\VkWidget;
use yii\helpers\TimeManager;

$TimeManager = new TimeManager();

$this->registerCssFile('@web/css/news.css', ['depends' => ['app\assets\AppAsset']]);

$this->title = "CyberPoint — Новости";
?>
<h1><strong>Новости CyberPoint</strong></h1>
<div class="content">
  <div class="row img-news">
    <div class="col-md-6 large">
      <div class="item" style="background-image: url(<?=  Yii::getAlias('@web').'/uploaded-files/news/'.$lastNews[0]->image; ?>)">
        <div class="info">
          <h2><a href="new-page?id=<?= $lastNews[0]->id?>" title="<?= $lastNews[0]->title ?>">
            <?= $lastNews[0]->title ?>
          </a></h2>
          <p><?= $TimeManager->SetTime($lastNews[0]->date)?></p>
          <? $lastNews[0]->game = ($lastNews[0]->game == '')?('cybersport'):($lastNews[0]->game); ?>
          <img class="game-icon" src="<?=  Yii::getAlias('@web').'/images/news/'.$lastNews[0]->game. '-icon.png'; ?>" alt="<?= $lastNews[0]->game ?> Новости">
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 thin">
      <div class="item" style="background-image: url(<?=  Yii::getAlias('@web').'/uploaded-files/news/'.$lastNews[1]->image; ?>)">
        <div class="info">
          <h2><a href="new-page?id=<?= $lastNews[0]->id?>" title="<?= $lastNews[0]->title ?>">
            <?= $lastNews[1]->title ?>
          </a></h2>
          <p><?= $TimeManager->SetTime($lastNews[1]->date)?></p>
          <? $lastNews[1]->game = ($lastNews[1]->game == '')?('cybersport'):($lastNews[1]->game); ?>
          <img class="game-icon" src="<?=  Yii::getAlias('@web').'/images/news/'.$lastNews[1]->game. '-icon.png'; ?>" alt="<?= $lastNews[1]->game ?> Новости">
        </div>
      </div>
    </div>

    <div class="col-md-6 col-sm-6 thin">
      <div class="item" style="background-image: url(<?=  Yii::getAlias('@web').'/uploaded-files/news/'.$lastNews[2]->image; ?>)">
        <div class="info">
          <h2><a href="new-page?id=<?= $lastNews[0]->id?>" title="<?= $lastNews[2]->title ?>">
            <?= $lastNews[2]->title ?>
          </a></h2>
          <p><?= $TimeManager->SetTime($lastNews[2]->date)?></p>
          <? $lastNews[2]->game = ($lastNews[2]->game == '')?('cybersport'):($lastNews[2]->game); ?>
          <img class="game-icon" src="<?=  Yii::getAlias('@web').'/images/news/'.$lastNews[2]->game. '-icon.png'; ?>" alt="<?= $lastNews[2]->game ?> Новости">
        </div>
      </div>
    </div>

  </div>
  <div class="row line-news">
    <? foreach($topNews as $new): ?>
      <div class="col-md-3 col-sm-6 item">
        <h2><a href="new-page?id=<?= $new->id?>" title="<?= $new->title ?>"><?= $new->title ?></a></h2>
        <p class="author_par">
          <?= $TimeManager->SetTime($new->date) ?>
        </p>
        <p>
          <?= $new->new_desc ?> 
        </p>
      </div>
    <? endforeach ?>
  </div>

  <? foreach($gameNews as $new): ?>
  <h3 class="title"><?= $new[0]->game ?></h3>
  <div class="row game-news">
    <div class="col-md-6 item large">
      <h2><a href="new-page?id=<?= $new[0]->id?>" title="<?= $new[0]->title ?>">
        <?= $new[0]->title ?>
        </a></h2>
      <p class="author_par">
        <?= $TimeManager->SetTime($new[0]->date) ?>
          </p>
    </div>

    <div class="col-md-6 item large item-wrapper">
      <? for($c=1; $c<5; $c++): ?>
      <div class="col-xs-6 item thin">
        <h2><a href="new-page?id=<?= $new[$c]->id?>" title="<?= $new[$c]->title ?>">
          <?= $new[$c]->title ?>
        </a></h2>
        <p class="author_par">
          <?= $TimeManager->SetTime($new[$c]->date) ?>
        </p>
      </div>
      <? endfor; ?>
    </div>
  </div>
  <? endforeach?>

</div>