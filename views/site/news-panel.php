<?php
use yii\web\User;

$this->registerCssFile('@web/css/admin-panel.css', ['depends' => ['app\assets\AppAsset']]);

$this->title = 'CyberPoint';
?>
<div class="site-index">
        <h1>Управление новостями</h1><br>
  <div class="row content">
    <?php require_once('php-files/news-panel-left-menu.php');?>
    <div class="col-md-9 col-sm-8">
      Выберите нужный пункт в меню слева.
    </div>

    </div>
</div>
