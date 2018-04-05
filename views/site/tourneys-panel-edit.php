<?php

use yii\web\User;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->registerCssFile('../css/edit-page.css');
$this->title = 'Управление событиями';

function getRights($tourney){
  if((Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "tourneysmoderator") 
  or strripos($tourney->rights, Yii::$app->user->identity->username) == true or $tourney->rights == Yii::$app->user->identity->username 
  or $tourney->organisator == Yii::$app->user->identity->username){
    return true;
  }else{
    return false;
  };
}
?>
<div class="site-index">
        <h1>Управление событиями</h1><br>
  <div class="body-content">
    <?php require_once('php-files/tourneys-panel-left-menu.php');?>
    <div class="col-md-9 col-sm-8">
          <?php Pjax::begin(); ?>
          <?php
          if(isset($message) and $message != ''){
          echo '<div class="alert alert-';
          echo (isset($alerttype))?($alerttype):("success");
          echo '">
                    '.$message.'
                    </div>';
          };
          ?>
      <div class="btn-group">
        <a href="tourneys-panel?part=edit&rights=all">
        <button type="button" class="btn btn-default" 
        <?=(isset($_GET['rights']))?(($_GET['rights'] == 'my')?('>'):'style="background-color: #e6e6e6; border-color: #adadad; color: #333;">'):'style="background-color: #e6e6e6; border-color: #adadad; color: #333;">'?>
        Все</button></a>
        <a href="tourneys-panel?part=edit&rights=my">
        <button type="button" class="btn btn-default" 
                <?=(isset($_GET['rights']))?(($_GET['rights'] == 'my')?('style="background-color: #e6e6e6; border-color: #adadad; color: #333;">'):'>'):'>'?>
        Мои</button></a>
      </div>
      
          <div class="row only_bot_border">
           <div class="col-xs-12 col-md-8">Заголовок:</div>
           <div class="col-xs-6 col-md-4">Дата: </div>
      </div>
      
    <?php foreach ($tourneys as $tourney): ?>
      <div class="row">
          <div class="col-xs-12 col-md-8">
          <?= Html::encode("{$tourney->title}")?>
          
          <?php if($tourney->status == 1){?>
          
          <a href="../tourneys/tourney-page?id=<?= $tourney->id?>" target="_blank">
          <span class="glyphicon glyphicon-ok icon_tourneys" style="color: #337ab7;" title="Новость опубликована"></span></a>
          <?php }elseif($tourney->status == 0){?>
          
          <span class="glyphicon glyphicon-eye-close icon_tourneys" title="Новость ожидает модерации"></span>
          
          <?php }else{;?>
          
          <span class="glyphicon glyphicon-minus icon_tourneys" title="Новость в процессе написания"></span>

          <?php };?>
          </div>
          <div class="col-xs-6 col-md-4">
          <span class="organisator_par"><?= Html::encode("{$tourney->date_actual_added}")?></span>
          
          <?php if(getRights($tourney) == true){ ?>
          <span class="glyphicon glyphicon-user icon_opt" style="color: #337ab7;" title="<?=$tourney->rights?>"></span></a>
          <?php };?>
          
          <span class="glyphicon glyphicon-book icon_opt" style="color: #337ab7;" title="<?=$tourney->organisator?>"></span>
          
          <?php if($tourney->status == 1){?>
          
          <a href="../tourneys/tourney-page?id=<?= $tourney->id?>" target="_blank">
          <span class="glyphicon glyphicon-eye-open icon_opt" style="color: #337ab7;" title="Посмотреть"></span></a>
           
           <?php }elseif(getRights($tourney) == true){?>
            
            <a href="../site/tourney-preview?id=<?= $tourney->id?>" target="_blank">
            <span class="glyphicon glyphicon-eye-open icon_opt" style="color: #337ab7;" title="Посмотреть"></span></a>
            
            <?php }else{?>
            
            <span class="glyphicon glyphicon-eye-close icon_opt" title="Новость не опубликована"></span>
            
            <?php };?>
            
          <?php if(getRights($tourney) == true){?>
          <a target="_blank" href="../site/tourney-editor?id=<?= $tourney->id?>"><span class="glyphicon glyphicon-pencil icon_opt" style="color: #337ab7;" title="Редактировать"></span></a>
            <?php };?>
            
          <?php if(Yii::$app->user->identity->accessToken == "admin" 
          or Yii::$app->user->identity->accessToken == "tourneysmoderator"){
            if($tourney->status == 0){  //WORD HERE?>
          <a href="../site/change-status-t?id=<?=$tourney->id?>&action=disapprove"><span class="glyphicon glyphicon-thumbs-down icon_opt" style="color: #337ab7;" title="Отклонить"></span></a>
          <a href="../site/change-status-t?id=<?=$tourney->id?>&action=approve"><span class="glyphicon glyphicon-thumbs-up icon_opt" style="color: #337ab7;" title="Одобрить"></span></a>
            <?php };};?>
            
          </div>
        </div>
        
        <?php 
        $anything = 1;
        endforeach;
        if(!isset($anything)){
          echo "<br><p align='center' style='color: #999;'>Записей нет</p>";
        };
        ?>
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
              <?php Pjax::end(); ?>
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
