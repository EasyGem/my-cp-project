<?php

use yii\web\User;

$this->registerCssFile('../css/edit-page.css');
$this->title = 'CyberPoint';
?>
<div class="site-index">
  <h1>Организации</h1><br>
  <div class="body-content">
    <?php require_once('php-files/tourneys-panel-left-menu.php');?>
    <div class="col-md-9 col-sm-8">
    
      <div class="row only_bot_border">
      
        <div class="col-xs-12 col-md-8">Организация:</div>
        <div class="col-xs-6 col-md-4" align="center">Кол-во событий: </div>
        
      </div>
      <?php 
      $i = 0;
      foreach($organisators as $organisator):?>
      <div class="row">
      
        <div class="col-xs-12 col-md-8">
        <?=$organisator['organisator']?>
        </div>
        
        <div class="col-xs-6 col-md-4">
        <p align="center"><?=$tourneyssnum[$i][0]["COUNT(0)"]?></p>
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