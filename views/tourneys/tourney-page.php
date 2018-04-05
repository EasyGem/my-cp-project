<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\LastTourneys;
use yii\widgets\VkWidget;
use yii\helpers\HtmlPurifier;

$curdate = $tddate[0]['CURDATE()'];
$tourney = $tourney[0];
$this->title = $tourney->title . ' | '. $tourney->town . ' | CyberPoint';
$descOfPage = "CyberPoint — Турнир по " . $tourney->discipline . ', ' . $tourney->town . '. ' . 'Организатор: ' . $tourney->organisator . 
'. ' . strip_tags($tourney->text);
$this->registerMetaTag(['name' => 'description', 'content' => $descOfPage]);

$this->registerCssFile('@web/css/page.css', ['depends' => ['app\assets\AppAsset']]);
$this->registerCssFile('@web/css/tourney-page.css', ['depends' => ['app\assets\AppAsset']]);

?>
  <p class="back">
    <a href="../tourneys/index">
      <i class="fa fa-angle-left"></i>
      Назад к турнирам
    </a>
  </p>

  <div class="row content">
    <div class="col-md-9">
      <div class="row">
        <h1 class="page-title">
          <strong><?= Html::encode("{$tourney->title}")?></strong> 
          <span class="games"><?= Html::encode("{$tourney->discipline}")?></span></h1>
          <p class="author_par">
            Добавлено <?= (new DateTime($tourney->date_added))->format('d.m.Y'); ?>
          </p>
          <div class="tourney-block">
            <div class="image col-sm-3 hidden-xs">
              <img src="<?=  Yii::getAlias('@web').'/images/tourneys/'. $tourney->image; ?>" alt="">
            </div>
            <div class="info col-sm-9">
              <div class="row">
                <?= $tourney->text ?>
              </div>
            </div> 
          </div>
          <div class="params">
            <hr>
            <div class="col-sm-8 left">
              <p><?= ($tourney->type == 'virtual')?('Интернет-турнир'):($tourney->town) ?><br>
                Начало: <?= (new DateTime($tourney->date_event))->format('d.m.Y H:i:s'); ?> МСК <br>
                <?= ($tourney->team_status == 0)?('Набор команд окончен'):('Идет набор команд ('. 
                (($tourney->compete_cost == '')?('бесплатное участие)'):('участие платное - '.$tourney->compete_cost).' на команду)')); ?>
              </p>
              <div class="regular"> 
                <?= $tourney->add_info?>
              </div>
            </div>
            <div class="col-sm-4 right">
              <?= ($tourney->prise_pool != '')?(
                '<p>Призовой фонд: <br>
                <span class="prize show-pinfo">
                '.$tourney->prise_pool.'
              </span></p>'):('') ?>

              <div class="prize-info">
                <?= $tourney->prise_info ?>
              </div>
              <a href="<?= $tourney->reg_link ?>" target="_blank" class="btn btn-primary take-part">Участвовать</a>
              <h3>
                <span>Организовано</span> 
                <br> 
                <?= $tourney->organisator ?>
              </h3>
              <p class="social-icons">
                <?= $tourney->socials ?>
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <?php 
          if($tourney->twitch_username != NULL){
            echo "<hr><p>Онлайн-трансляция:</p>";
            echo "<iframe src='https://player.twitch.tv/?channel="
            . $tourney->twitch_username .
            "' frameborder='0' allowfullscreen='true' 
            scrolling='no' height='378' width='620' id='twitch_stream'></iframe>";
          };

          if($tourney->organisator !== $tourney->rights){
            echo '<hr><div id="myAlert5" class="alert alert-success" style="background: #eee; border-color: #ddd; color: #777; font-size: 14px; padding: 10px;">
            Если вы являетесь организатором этого или другого турнира на нашем сайте, 
            то вы можете <a href="../site/contact">запросить аккаунт организатора</a>, чтобы самостоятельно публиковать ваши события на нашем сайте.
            </div>';}
            ?>
          <hr>
          <p class="back">
            <a href="../tourneys/index">
              <i class="fa fa-angle-left"></i>
              Назад к турнирам
            </a>
          </p>
          </div>
        </div>  
        <div class="col-md-3 sider hidden-sm hidden-xs">
          <? //LastTourneys::widget();?>
          <hr>
          <?=VkWidget::widget();?>
        </div>
      </div>