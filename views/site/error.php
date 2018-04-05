<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
Yii::$app->response->statusCode = 404;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Произошла ошибка при загрузке страницы.
    </p>
    
        <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
</div>
