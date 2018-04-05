<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
Yii::$app->response->statusCode = 404;
$this->title = 'Page not found.';
?>
<div class="site-error">

  <h1>Not Found (#404)</h1>

  <div class="alert alert-danger">
    Page not found.
  </div>

  <p>
    Страницы по данному адресу не существует.
  </p>

</div>
