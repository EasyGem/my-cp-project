<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\VkWidget;

$this->title = "CyberPoint — Контакты";
?>
<style>
#zao{
  cursor: pointer;
  text-decoration: underline;
}
#zao:hover{
  text-decoration: none;
}
</style>
<div class="row content">
  <div class="col-md-8">
    <h1 class="pageTitle">Контакты</h1>
    <hr>
    <?php
    if(isset($message)){
      echo '<div class="alert alert-success';
      echo '">
      '.$message.'
      </div>';
    };
    ?>
    <p>Если у вас есть предложения, вопросы или замечания по поводу работы 
    сайта, вы можете обратиться к нам.</p>
    <p>Также, если вы являетесь организатором киберспортивных туринров, 
      вы можете запросить аккаунт организатора на нашем сайте, который позволит вам 
      создавать и редактировать объявления о ваших турнирах. 
      Для этого в теме сообщения укажите "<span id="zao"  title="Нажмите для вставки">Запрос аккаунта организации</span>"</p>
      <p>Также вы можете связаться с нами напрямую по почте <b>admin@cybpoint.ru</b></p>

      <?php
      $form = ActiveForm::begin(); ?>

      <?= $form->field($model, 'topic')->label('Тема'); ?>

      <?= $form->field($model, 'email')->label('Ваш e-mail')
      ->hint('E-mail для обратной связи'); ?>

      <?= $form->field($model, 'text')->label('Текст сообщения')
      ->textarea(['rows' => 2, 'cols' => 5]);
      ?>

      <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
      </div>

      <?php ActiveForm::end(); ?>

    </div>
    <div class="side_bar col-md-4 hidden-sm hidden-xs">
      <?=VkWidget::widget();?>
    </div>
    <script>
      $("#zao").click(function(){
        $("#contactform-topic").val($("#zao").text());
      });
    </script>
  </div>
