<?php

use yii\web\User;

$this->title = 'Панель управления';
?>
<div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="../site/site-panel">Обзор</a></li>
            <li><a href="../site/site-panel?part=users">Пользователи</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="margin-left: 0;">
          <h1 class="page-header">Панель управления</h1>

          <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
              <h4><a target="_blank" href="../news/new-page?id=<?=$info['lastnew'][0]['id']?>">Последняя новость</a></h4>
              <span class="text-muted"><?=$info['lastnew'][0]['title']?></span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <h4><a target="_blank" href="../tourneys/tourney-page?id=<?=$info['lasttourney'][0]['id']?>">Последний турнир</a></h4>
              <span class="text-muted"><?=$info['lasttourney'][0]['title']?></span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <h4>Empty</h4>
              <span class="text-muted">No info</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <h4>Empty</h4>
              <span class="text-muted">No info</span>
            </div>
          </div>

          <h2 class="sub-header">Empty header</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1,001</td>
                  <td>Lorem</td>
                  <td>ipsum</td>
                  <td>dolor</td>
                  <td>sit</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
