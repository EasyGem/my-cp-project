<?php

use yii\web\User;

$this->registerCssFile('@web/css/index.css', ['depends' => ['app\assets\AppAsset']]);

$this->registerCssFile('@web/css/template-fix.css', ['depends' => ['app\assets\AppAsset']]);

$this->title = 'CyberPoint - киберспортивные новости и турниры';
?>
</div> <!-- TEMPLATE FIX-->

<div class="bg-layer">
<div class="container">
  <div class="site-index">
    <div class="jumbotron">

      <div class="row"><h1>Добро пожаловать в CyberPoint!</h1>
        <p class="lead">Здесь вы можете читать свежие новости киберспорта, а также находить и публиковать турниры.
        </p>
      </div>

      <div class="row sections">
        <div class="col-md-6 col-md-offset-0 item">
          <p><a class="btn btn-lg btn-info" href="<?=  Yii::getAlias('@web').'/tourneys/index'; ?>">Перейти к турнирам</a></p>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta facere autem animi aliquid eaque at esse eius earum. Amet, odit!</p>
        </div>
        <div class="col-md-6 item">
          <p><a class="btn btn-lg btn-info" href="<?=  Yii::getAlias('@web'). '/news/index'; ?>">Перейти к новостям</a></p>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta facere autem animi aliquid eaque at esse eius earum. Amet, odit!</p>
        </div>
      </div>


    </div>
  </div>
</div>
</div>

<div><!-- TEMPLATE FIX-->