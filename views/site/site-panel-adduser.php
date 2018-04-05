<?php
use yii\widgets\ActiveForm;
use yii\web\User;
use yii\helpers\Html;

$this->title = 'Добавить пользователя';

$i = 0;
while(isset($access[$i])){
  $accessdroplist[$access[$i]['accessToken']] = $access[$i]['accessToken'];
  $i++;
};
unset($i);
?>
<div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="../site/site-panel">Обзор</a></li>
            <li><a href="../site/site-panel?part=users">Пользователи</a></li>
            <hr>
            <li><a href="../site/site-panel?action=adduser">Добавить пользователя</a></li>
            <li><a href="../site/site-panel?action=removeuser">Удалить пользователя</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="margin-left: 0;">
          <h1 class="page-header">Добавить пользователя</h1>
          <?php
          if(isset($message)){
          echo '<div class="alert alert-';
          echo (isset($alerttype))?($alerttype):("success");
          echo '">
                    '.$message.'
                    </div>';
          };
          ?>
          <div class="table-responsive">
          <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'username')->label('Логин');?>     

            <?= $form->field($model, 'password')->label('Пароль')->input('password'); ?>
            
            <?= $form->field($model, 'rep_password')->label('Повторите пароль')->input('password'); ?>
                    
            <?= $form->field($model, 'accessToken')
            ->label('Доступ')
            ->dropDownList($accessdroplist,
            [
                'prompt' => 'Доступ'
            ]);?>
            
            <?= $form->field($model, 'contact_link')->label('Ссылка на контакты')
            ->hint('Не обязательно.'); ?>
          
                <?= Html::SubmitButton('Готово', ['class' => 'btn btn-primary']); ?>
                
          <?php ActiveForm::end();?>
          </div>
        </div>
      </div>
