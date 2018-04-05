<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\LastNews;
use yii\helpers\HtmlPurifier;
use yii\widgets\VkWidget;
use yii\helpers\TimeManager;

$TimeManager = new TimeManager();
$new = $model;

$this->registerCssFile('@web/css/page.css', ['depends' => ['app\assets\AppAsset']]);

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
</div>