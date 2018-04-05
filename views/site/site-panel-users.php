<?php

use yii\web\User;

$this->title = 'Пользователи';
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
          <h1 class="page-header">Пользователи</h1>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#ID</th>
                  <th>Username</th>
                  <th>Access Token</th>
                  <th>Contact Link</th>
                  <th>News/Tourneys</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $i = 0;
                foreach($users as $user): ?>
                <tr>
                  <td><?= $user['id'] ?></td>
                  <td><?= $user['username'] ?></td>
                  <td><?= $user['accessToken'] ?></td>
                  <td><?= ($user['contact_link'] == NULL)?('Нет'):
                  ("<a href='".$user['contact_link']."' target='_blank'>".$user['contact_link']."</a>") ?></td>
                  <td><?= $usersNT[$i]['N'][0]["COUNT(0)"].' / '.$usersNT[$i]['T'][0]["COUNT(0)"] ?></td>
                </tr>
                <?php 
                $i++;
                endforeach; 
                unset($i); ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
