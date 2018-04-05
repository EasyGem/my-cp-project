<?php

use yii\web\User;

$this->registerCssFile('@web/css/admin-panel.css', ['depends' => ['app\assets\AppAsset']]);
$this->title = 'CyberPoint';
?>
<div class="site-index">
        <h1>Авторы</h1><br>
  <div class="body-content content">
    <?php require_once('php-files/news-panel-left-menu.php');?>
    <div class="col-md-9 col-sm-8">
    
      <div class="row">
      
        <div class="col-xs-12 col-md-8"><b>Автор:</b></div>
        <div class="col-xs-6 col-md-4" align="center"><b>Кол-во публикаций: </b></div>
        
      </div>
      <?php 
      $i = 0;
      foreach($users as $user):?>
      <div class="row authors">
      
        <div class="col-xs-12 col-md-8">
        <?=$user['author']?>
        </div>
        
        <div class="col-xs-6 col-md-4">
        <p align="center"><?=$newsnum[$i][0]["COUNT(0)"]?></p>
        </div>
        
    </div>
    <?php 
    $i++;
    endforeach;?>
    </div>
</div>
    <style>
    .icon_tourneys{
  float: left;
  cursor: pointer;
  padding-right:10px;
}
    </style>
</div>