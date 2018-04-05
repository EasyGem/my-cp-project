<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\User;
use kartik\date\DatePicker;
use vova07\imperavi\Widget;
use yii\helpers\Url;

$this->registerCssFile('@web/css/admin-panel.css', ['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile('@web/js/admin-panel.js', ['depends' => ['app\assets\AppAsset']]);
$this->title = 'Редактировать новость';

$games = (new \yii\db\Query())
->select('name')
->from('games')
->all();

$i = 0;
while(isset($games[$i])) {
  $gameList .= ($i>0)?(', '.$games[$i]['name']):($games[$i]['name']);
  $i++;
}

?>

<div class="site-index">
  <h1>Редактировать новость</h1><br>
  <div class="body-content content">
    <?php require_once('php-files/news-panel-left-menu.php');?>
    <div class="col-md-9 col-sm-8">
      <div class="site-news-panel-add">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
        if(isset($message)){
          echo '<div class="alert alert-';
          echo (isset($alerttype))?($alerttype):("success");
          echo '">
          '.$message.'
          </div>';
        };
        ?>
        <?= $form->field($model, 'id')->label('ID')
        ->input('text', ['readonly' => true, 'value' => (isset($id))?$id:$model->id, 'style' => 'width: 80px; text-align: center;']); ?>

        <?= $form->field($model, 'title')
        ->textInput(['maxlength' => 150])
        ->label('Название'); ?>

        <?= $form->field($model, 'type')->label('Тематика')
        ->radioList([
          'general' => 'Общая',
          'gaming' => 'Игровая'
        ])
        ->hint('Если новость касается какой-то конкретной игры, то укажите "Игровая".'); ?>

        <?= $form->field($model, 'game', ['options' => ['class' => 'hidden']])->label('Игра')
        ->input('text')
        ->hint("Игры, добавленные в базу данных сайта, следует по образцу: ". $gameList); ?>

        <?= $form->field($model, 'text')->widget(Widget::className(), [
          'settings' => [
            'lang' => 'ru',
            'minHeight' => 300,
                         // 'imageUpload' => Url::to(['/site/image-upload']),
                          //'imageManagerJson' => Url::to(['/site/images-get']),
            'pasteLinkTarget' => '_blank',
            'linkNofollow' => true,
            'plugins' => [
              'clips',
                         // 'imagemanager',
              'table',
              'fullscreen'
            ],
          ]
        ])
        ->label('Содержимое')
        ->hint('Используйте HTML-тэги для разметки.');
        ?>  

        <?= $form->field($model, 'new_desc')
        ->textarea()
        ->label('Описание новости')
        ->hint('Краткий обзор новости. Без HTML-тэгов. Максимум 180 символов.');
        ?>

        <?= $form->field($model, 'file')->label('Изображение')
        ->fileInput(); ?>        

        <?= $form->field($model, 'image')->label(false)
        ->input('text', ['readonly' => true]); ?>   

        <?= $form->field($model, 'method')
        ->label(false)
        ->radioList([
          'save' => 'Save',
          'test' => 'Test',
          'publish' => 'Publish',
          ], ['style' => 'display: none;']);?>
          
          <div class="form-group">
            <?= Html::Button('Сохранить', ['class' => 'btn btn-primary', 'id' => 'save_sub']); ?>
            <?= Html::Button('Проверить', ['class' => 'btn btn-primary', 'id' => 'test_sub']); ?>
            <?= Html::Button('Готово', ['class' => 'btn btn-primary', 'id' => 'publish_sub']) ?>
          </div>
          <?php ActiveForm::end();?>
        </div>
      </div>
    </div>
  </div>
  <script>
    $("#save_sub").click(function(){
      $("input[value='save']").prop('checked', true);
      $("#w0").prop('target', '_self');
      $("#w0").submit();
    });
    $("#test_sub").click(function(){
      $("#w0").prop('target', '_blank');
      $("input[value='test']").prop('checked', true);
      $("#w0").submit();
    });
    $("#publish_sub").click(function(){
      $("input[value='publish']").prop('checked', true);
      $("#w0").prop('target', '_self');
      $("#w0").submit();
    });
  </script>