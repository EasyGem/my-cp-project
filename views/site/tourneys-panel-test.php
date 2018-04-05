<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\LastTourneys;

$tourney = $model;
$this->title = "CyberPoint — ".$tourney->title;
?>
<a href="../tourneys/index">&laquo; Назад к турнирам<br></a>
<div class="newsBlock">
<hr>
         <h3><?= Html::encode("{$tourney->title}")?>
        <span class="author_par"><?= Html::encode("{$tourney->discipline}")?></span></h3>
        <p class="author_par">Добавлено: <?= date('Y-m-d')?></p>
        <?php 
        if($imgUrl != NULL){
          echo "<p align='center'><img style='max-width:100%;' src=../". $imgUrl ."></p>";
        };
        ?>
        <p><?= $tourney->text?></p>
        
         <div class="change_bar">
         <div class="change_block" id="visitors_change">Посетителям</div>
         <div class="change_block" id="players_change">Игрокам</div>
         </div>
        <div class="tour_desc">
        
        <!-- VISITORS INFO !-->
        <span id="visitors_text">
        <p><?= $tourney->desc_visitors?></p>
        <p>Место проведения: <?= $tourney->town?>, <?= $tourney->location?></p>
        <p>Дата проведения: <?= $tourney->date_event?></p>
        <p><?php if($tourney->ticket_cost != "0"){ 
        echo "Стоимость билета: "; 
        if($tourney->ticket_cost != "1" and $tourney->ticket_cost != "0"){echo $tourney->ticket_cost;}else{echo "Бесплатно";};
        }else{echo "Все билеты проданы";};?></p>
        <?php if($tourney->buy_ticket_link != NULL and $tourney->ticket_cost != "0" and $tourney->ticket_cost != "1"): ?>
        <p><a href="<?=$tourney->buy_ticket_link?>" target="_blank"><input type="button" class="buy_button" value="Купить билет"></a></p>
        <?php endif; ?>
        </span>
        
        <!-- PLAYERS INFO !-->
        <span id="players_text" style="display:none;">
        <p><?= $tourney->desc_players?></p>
        <p>Место проведения: <?= $tourney->town?>, <?= $tourney->location?></p>
        <p>Дата проведения: <?= $tourney->date_event?></p>
        <p><?php if($tourney->team_status != "0"){ echo "Стоимость участия: ".$tourney->compete_cost;}else{echo "Набор команд окончен";};?></p>
        <?php if($tourney->reg_team_link != NULL and $tourney->team_status != "0"): ?>
        <p><a href="<?=$tourney->reg_team_link?>" target="_blank"><input type="button" class="buy_button" id="reg_team_button" value="Подать заявку"></a></p>
        <?php endif; ?>
        </span>
        </div><br>

        <?php if($tourney->organisator != ''):?>
        <p>Компания-организатор: <?= $tourney->organisator?></p>
        <?php endif;?>
        <?php if($tourney->contact != ''):?>
        <p>Контакты: <?= $tourney->contact?></p>
        <?php endif;?>
        <hr>
        <?php 
        if($tourney->translation != NULL){
          echo "<p>Онлайн-трансляция:</p>";
          echo "<iframe src='https://player.twitch.tv/?channel=$tourney->translation' frameborder='0' allowfullscreen='true' 
          scrolling='no' height='378' width='620'></iframe>";
        };
        ?>

        <hr>
       <a href="../tourneys/index">&laquo; Назад к турнирам<br></a>
</div>
<div class="side_bar">
        <?=LastTourneys::widget();?>
<hr>
</div>
<script>
$(document).ready(function(){
  $("#players_change").click(function showPlayersInfo(){
    $("#visitors_text").hide();
    $("#players_text").show();
    $("#visitors_change").css("background", "#aaa");
    $("#players_change").css("background", "#337ab7");
    
    $("#visitors_change").click(function showVisitorsInfo(){
     $("#players_text").hide();
     $("#visitors_text").show();
     $("#visitors_change").css("background", "#337ab7");
     $("#players_change").css("background", "#aaa");
   });
  });
});
</script>