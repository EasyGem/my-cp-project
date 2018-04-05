<?php

use yii\web\User;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->registerCssFile('@web/css/admin-panel.css', ['depends' => ['app\assets\AppAsset']]);
$this->title = 'Управление новостями';

function getRights($new){
  if((Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "newsmoderator") 
  or strripos($new->rights, Yii::$app->user->identity->username) == true or $new->rights == Yii::$app->user->identity->username 
  or $new->author == Yii::$app->user->identity->username){
    return true;
  }else{
    return false;
  };
}
?>
<div class="site-index">
        <h1>Управление новостями</h1><br>
  <div class="body-content content">
    <?php require_once('php-files/news-panel-left-menu.php');?>
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
        <a href="news-panel?part=edit&rights=all">
        <button type="button" class="btn btn-default" 
        <?=(isset($_GET['rights']))?(($_GET['rights'] == 'my')?('>'):'style="background-color: #e6e6e6; border-color: #adadad; color: #333;">'):'style="background-color: #e6e6e6; border-color: #adadad; color: #333;">'?>
        Все</button></a>
        <a href="news-panel?part=edit&rights=my">
        <button type="button" class="btn btn-default" 
                <?=(isset($_GET['rights']))?(($_GET['rights'] == 'my')?('style="background-color: #e6e6e6; border-color: #adadad; color: #333;">'):'>'):'>'?>
        Мои</button></a>
      </div>
      
          <div class="row only_bot_border">
           <div class="col-xs-12 col-md-8">Заголовок:</div>
           <div class="col-xs-6 col-md-4">Дата: </div>
      </div>
      
    <?php foreach ($news as $new): ?>
      <div class="row">
          <div class="col-xs-12 col-md-7">
          <?= Html::encode("{$new->title}")?>
          
          <?php if($new->ready == 1){?>
          
          <a href="../news/new-page?id=<?= $new->id?>" target="_blank">
          <span class="glyphicon glyphicon-ok icon_news" style="color: #337ab7;" title="Новость опубликована"></span></a>
          <?php }elseif($new->ready == 0){?>
          
          <span class="glyphicon glyphicon-eye-close icon_news" title="Новость ожидает модерации"></span>
          
          <?php }else{;?>
          
          <span class="glyphicon glyphicon-minus icon_news" title="Новость в процессе написания"></span>

          <?php };?>
          </div>
          <div class="col-xs-6 col-md-5">
          <span class="author_par"><?= Html::encode("{$new->date}")?></span>
          
          <span class="glyphicon glyphicon-user icon_opt" style="color: #337ab7;" title="<?=$new->author?>"></span>
          
          <?php if($new->ready == 1){?>
          
          <a href="../news/new-page?id=<?= $new->id?>" target="_blank">
          <span class="glyphicon glyphicon-eye-open icon_opt" style="color: #337ab7;" title="Посмотреть"></span></a>
           
           <?php }elseif(getRights($new) == true){?>
            
            <a href="../site/new-preview?id=<?= $new->id?>" target="_blank">
            <span class="glyphicon glyphicon-eye-open icon_opt" style="color: #337ab7;" title="Посмотреть"></span></a>
            
            <?php }else{?>
            
            <span class="glyphicon glyphicon-eye-close icon_opt" title="Новость не опубликована"></span>
            
            <?php };?>
            
          <?php if(getRights($new) == true){?>
          <a target="_blank" href="../site/new-editor?id=<?= $new->id?>"><span class="glyphicon glyphicon-pencil icon_opt" style="color: #337ab7;" title="Редактировать"></span></a>
            <?php };?>
            
          <?php if(Yii::$app->user->identity->accessToken == "admin" 
          or Yii::$app->user->identity->accessToken == "newsmoderator"){
            if($new->ready == 0){  //WORD HERE?>
          <a href="../site/change-status?id=<?=$new->id?>&action=disapprove"><span class="glyphicon glyphicon-thumbs-down icon_opt" style="color: #337ab7;" title="Отклонить"></span></a>
          <a href="../site/change-status?id=<?=$new->id?>&action=approve"><span class="glyphicon glyphicon-thumbs-up icon_opt" style="color: #337ab7;" title="Одобрить"></span></a>
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
    .icon_news{
  float: left;
  cursor: pointer;
  padding-right:10px;
}
    </style>
</div>
