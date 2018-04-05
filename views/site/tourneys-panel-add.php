<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\User;
use app\assets\AppAsset;
use kartik\date\DatePicker;
use vova07\imperavi\Widget;

$this->title = 'Управление событиями';
?>

<div class="site-index">
  <h1>Добавить событие</h1><br>
  <div class="body-content">
    <?php require_once('php-files/tourneys-panel-left-menu.php');?>
    <div class="col-md-9 col-sm-8">
      <div class="site-news-panel-add">
      
          <?php $form = ActiveForm::begin();
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
            
            <?= $form->field($model, 'organisator')->label('Организатор')
            ->input('text', ['readonly' => (Yii::$app->user->identity->accessToken == 'organisator')?(true):(false), 'value' => (isset($model->organisator))?$model->organisator:(Yii::$app->user->identity->username)]); ?>
            
            <?= $form->field($model, 'title')->label('Название')->input([]); ?>
            
            <?= $form->field($model, 'town')->label('Город')
            ->hint('Название города проведения мероприятия.'); ?>
            
            <?= $form->field($model, 'location')->label('Место проведения')
             ?>
            
            <?=  $form-> field($model, 'date_event')
            ->label('Дата проведения')
            ->widget(DatePicker::classname(), [
                    'options' => [],
                    'language' => 'ru',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd', 
                    ]
                ]);
            ?>  
            
            <?=  $form-> field($model, 'date_last_day_event')
            ->label('Последний день мероприятия')
            ->widget(DatePicker::classname(), [
                    'options' => [],
                    'language' => 'ru',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd', 
                    ]
                ])
                ->hint('Указать, если мероприятие длится более одного дня.');
            ?> 
            
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
                  ->label('Содержимое'); ?>
                    
            <?= $form->field($model, 'discipline')
              ->label("Дисциплины")
          ->checkboxList([
              'Dota 2' => 'Dota 2',
              'CS:GO' => 'CS:GO',
              'WoT' => 'WoT',
              'Hearthstone' => 'Hearthstone',
              'LoL' => 'LoL'
          ]);?>
                    
            <?= $form->field($model, 'desc_visitors')->label('Описание для посетителей')
                    ->widget(Widget::className(), [
                      'settings' => [
                          'lang' => 'ru',
                          'minHeight' => 150,
                          'plugins' => [
                          ],
                      ]
                  ]); ?>
                    
            <?= $form->field($model, 'desc_players')->label('Описание для игроков')
                    ->widget(Widget::className(), [
                      'settings' => [
                          'lang' => 'ru',
                          'minHeight' => 150,
                          'plugins' => [
                          ],
                      ]
                  ]); ?>
                    
            <?= $form->field($model, 'compete_cost')->label('Стоимость участия')
            ->hint('Не обязятельно.'); ?>
                    
            <?= $form->field($model, 'team_status')->label(false)
                    ->radioList([
                        '1' => 'Идет набор команд',
                        '0' => 'Набор команд окончен',
                    ]); ?>
            
            <?= $form->field($model, 'ticket_cost')->label('Стоимость билета')
                    ->input('text', ['style' => 'width: 50%;'])
                    ->hint('Если билеты и цены могут быть разные разные, укажите стоимость в формате "100-200".'); ?>
                    
            <?= $form->field($model, 'tickstatus')
                ->label(false)
            ->radioList([
                'free' => 'Бесплатно',
                'gone' => 'Все билеты проданы',
            ]);?>
            
            <?= $form->field($model, 'buy_ticket_link')->label('Ссылка на страницу для покупки билета')
            ->hint('Не обязятельно.'); ?>
            
            <?= $form->field($model, 'reg_team_link')->label('Ссылка на страницу для подачи заявки')
            ->hint('Не обязятельно.'); ?>
                    
            <?= $form->field($model, 'contact')->label('Контактные данные')
                    ->hint('Ссылка на страницу в соц. сети или адрес почты.'); ?>     

                    
            <?= $form->field($model, 'file')->label('Изображение')
            ->fileInput(); ?>        
                    
            <?= $form->field($model, 'image')->label(false)
            ->input('text', ['readonly' => true]); ?>   
                    
            <?= $form->field($model, 'translation')->label('Название канала с трансляцией в Twitch')
            ->hint('Пример: dreamhackdota2_ru. Не обязательно.'); ?>
                    
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
$("input[value='free']").click(function(){
  $("input[name='TourneysAddForm[ticket_cost]']").val(1);
    $("input[name='TourneysAddForm[ticket_cost]']").prop('readonly', true);
});
$("input[value='gone']").click(function(){
  $("input[name='TourneysAddForm[ticket_cost]']").val(0);
  $("input[name='TourneysAddForm[ticket_cost]']").prop('readonly', true);
});
$("#tourneysaddform-ticket_cost").click(function(){
  $("input[value='free']").prop('checked', false);
  $("input[value='gone']").prop('checked', false);
  $("input[name='TourneysAddForm[ticket_cost]']").val(null);
  $("input[name='TourneysAddForm[ticket_cost]']").prop('readonly', false);
});
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