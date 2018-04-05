<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\LastNews;
use yii\helpers\HtmlPurifier;
use yii\widgets\VkWidget;
use yii\helpers\TimeManager;

$TimeManager = new TimeManager();

$this->registerCssFile('@web/css/page.css', ['depends' => ['app\assets\AppAsset']]);

$new = $new[0];
$this->title = $new->title." — CyberPoint";
?>
<p class="back">
  <a href="../news/index">
    <i class="fa fa-angle-left"></i>
    Назад к новостям
  </a>
</p>
<div class="content">
  <div class="row">
    <div class="col-md-8">
      <h1 class="page-title">
        <strong><?= $new->title?></strong>
      </h1>
      <p class="author_par">
        <?= $new->author. " | " .$TimeManager->SetTime($new->date) ?></p>
      <?= $new->text ?>
<br>
      <p class="back">
        <a href="../news/index">
          <i class="fa fa-angle-left"></i>
          Назад к новостям
        </a>
      </p>
    </div>

    <div class="col-md-4 sider hidden-sm hidden-xs">
    </div>

  </div>

  <div class="row read-more">
    <hr>
    <? for($c=0; $c<3; $c++): ?>

    <div class="col-md-4 col-sm-6">
      <div class="another-new">
        <a href="?id=<?= $gameNews[$c]->id ?>" title="<?= $gameNews[$c]->title ?>">
          <div class="image" style="background-image: url(<?=  Yii::getAlias('@web').'/uploaded-files/news/'.$gameNews[$c]->image; ?>)">
            
          </div>
          <h2><?= $gameNews[$c]->title ?></h2></a>
          <p class="author_par"><?= $TimeManager->SetTime($gameNews[$c]->date)?></p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
      <div class="another-new">
        <a href="?id=<?= $topNews[$c]->id ?>" title="<?= $topNews[$c]->title ?>">
          <div class="image" style="background-image: url(<?=  Yii::getAlias('@web').'/uploaded-files/news/'.$topNews[$c]->image; ?>)">
            
          </div>
          <h2><?= $topNews[$c]->title ?></h2></a>
          <p class="author_par"><?= $TimeManager->SetTime($topNews[$c]->date)?></p>
        </div>
      </div>

      <? endfor; ?>
              </div>
            </div>