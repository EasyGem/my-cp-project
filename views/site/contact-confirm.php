<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = "CyberPoint — Контакты";
?>
<h1 class="pageTitle">Контакты</h1>
<div class="newsBlock">
<hr>
<p>Вы ввели следующую информацию:</p>

<label>Name</label>: <?= Html::encode($model->topic) ?>
<br>
<label>Email</label>: <?= Html::encode($model->email) ?>


</div>
<div class="side_bar">
<h3>Зарегистрированные организации:</h3>
</div>
