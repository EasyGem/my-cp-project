<?php

use yii\web\User;

$this->registerCssFile('@web/css/admin-panel.css', ['depends' => ['app\assets\AppAsset']]);

$this->title = 'CyberPoint';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Панель управления</h1>
    </div>

        <div class="row">
            <div class="col-lg-4 item content">
                <h2>Новости</h2>

                <p><a class="btn btn-default" href="news-panel">Управление новостями &raquo;</a></p>
            </div>
            <div class="col-lg-4 item content">
                <h2>Турниры</h2>

                <p><a class="btn btn-default" href="tourneys-panel">Управление турнирами &raquo;</a></p>
            </div>
            <div class="col-lg-4 item content">
                <h2>Пользователи и статистика</h2>

                <p><a class="btn btn-default" href="site-panel">Просмотр &raquo;</a></p>
            </div>
        </div>
</div>
