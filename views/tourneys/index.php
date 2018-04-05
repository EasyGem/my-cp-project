<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\web\Request;
use yii\helpers\Url;
use yii\widgets\LastTourneys;
use yii\widgets\VkWidget;
use yii\widgets\Pjax;

$this->registerCssFile('@web/css/tourneys.css', ['depends' => ['app\assets\AppAsset']]);
$this->registerCssFile('@web/css/template-fix.css', ['depends' => ['app\assets\AppAsset']]);
$this->registerJsFile('@web/js/tourneys.js', ['depends' => ['app\assets\AppAsset']]);

$i  = 0;
while(isset($town[$i]))
{
  $towndroplist[$town[$i]['town']] = $town[$i]['town'];
  $i++;
};
unset($i);

$this->title = "Киберспротивные турниры — CyberPoint";
$curdate = $tddate;
$istheretourneys = false;
?>
</div> <!-- TEMPLATE FIX-->
<header class="header" style="background-image: url(<?=  Yii::getAlias('@web').'/images/tourneys/'.$gImage; ?>)">
  <div class="container">
    <div class="row">
      <h1><strong><?= $gTitle ?></strong></h1>
      <h2><?= $gDesc ?></h2>
    </div>
  </div>
  <ul class="game-list">
    <li id="dota"><a href="?game=dota">Dota 2</a>
    </li><li id="cs"><a href="?game=cs">CS:GO</a>
    </li><li id="lol"><a href="?game=lol">LoL</a>
    </li><li id="hs"><a href="?game=hs">Hearthstone</a>
    </ul>
  </header>
  <div class="container content">
    <p>
      <button class="btn btn-primary" id="show-filters">
        <i class="fa fa-cog"></i> Фильтры
      </button>
    </p>
    <div class="filters">
      <?php
      $request = Yii::$app->request;
      $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['tourneys/index']
      ]); 

      if(isset($_GET['TourneyOptions'])){
       $get_array = $_GET['TourneyOptions'];
     }else{
      $get_array = ["search_text" => "", 
      "town" => "", 
      "tense" => "", 
      "status" => "",
      "type" => ""]; 
    };
    $model->search_text = (isset($get_array['search_text']))?($get_array['search_text']):('');
    $model->town = (isset($get_array['town']))?($get_array['town']):('');
    $model->tense = (isset($get_array['tense']))?($get_array['tense']):('');
    $model->status = (isset($get_array['status']))?($get_array['status']):('');
    $model->type = (isset($get_array['type']))?($get_array['type']):('');
    $model->game = (isset($_GET['game']))?($_GET['game']):($get_array['game']);
    ?>
    <div class="wrapper">
      <div class="status">
        <?= $form->field($model, 'status', ['errorOptions' => ['tag' => null]])
        ->label(false)
        ->checkboxList([
          'team' => 'Идет набор команд',
          'free' => 'Только бесплатные турниры',
          ]);?>

        </div>
      </div>
      <div class="wrapper-fields">
        <div class="hidden-code-type hidden">
          <?= $form->field($model, 'type')
        ->label(false)
        ->radioList([
          'all' => 'all',
          'virtual' => 'virtual',
          'local' => 'local',
          ]);?>

          <?= $form->field($model, 'game')
              ->label(false)
              ->input('text'); ?>
        </div>

        <div class="location-choice">
          <ul>
            <li id="allT">Все
            </li><li id="virtualT">Виртуальные
            </li><li class="local" id="localT">
              <?= $form->field($model, 'town', ['errorOptions' => ['tag' => null]])
              ->label(false)
              ->dropDownList((isset($towndroplist))?($towndroplist):([''=>'']),
                [
                  'prompt' => 'Локальные ▼'
                  ]);?>
                </li>
              </ul>
            </div>

            <div class="search-field">
              <?= $form->field($model, 'search_text', ['errorOptions' => ['tag' => null]])
              ->label(false)
              ->input('text', ['placeholder' => "Поиск по названию", 'maxlength' => "150"]); ?>
            </div>

            <div class="tense">
              <?= $form->field($model, 'tense', ['errorOptions' => ['tag' => null]])
              ->label(false)
              ->dropDownList([
                'fut' => 'Запланированные турниры',
                'all' => 'Все турниры',
                'past' => 'Прошедшие турниры',
                'pres' => 'Идущие турниры',
                ]);?>
              </div>
            </div>

            <div class="form-group wrapper">
              <?php if(isset($_GET['TourneyOptions'])){
                echo '<a href="index">';
                echo Html::Button('Сбросить', ['class' => 'btn btn-primary', 'id' => 'grey_button']);
              echo '</a>';};?>
              <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
          </div>
          <hr class="filters-hr">

          <div class="col-md-8">

            <?php foreach ($tourneys as $tourney): ?>
              <div class="tourney-block row">
                <div class="image col-sm-3 hidden-xs">
                  <img src="<?=  Yii::getAlias('@web').'/images/tourneys/'.$tourney->image; ?>" alt="">
                </div>
                <div class="info col-sm-9">
                  <div class="row">
                    <h3><a href="<?php echo "tourney-page?id=".$tourney->id ?>"><?= Html::encode("{$tourney->title}")?></a></h3>
                    <p>
                      <?php
                      $text = $tourney->text;
                      if(mb_strlen($text)>300){
                        echo strip_tags(mb_substr($text, 0, 250));
                        echo '...';
                      }else{
                        echo strip_tags($text);
                      };
                      ?>
                    </p>
                  </div>
                  <div class="params row">
                    <div class="col-xs-8 left">
                      <p><?= ($tourney->type == 'virtual')?('Интернет-турнир'):($tourney->town) ?><br>
                        Начало: <?= (new DateTime($tourney->date_event))->format('d.m.Y H:i:s'); ?> МСК <br>
                        <?= ($tourney->team_status == 0)?('Набор команд окончен'):('Идет набор команд ('. 
                        (($tourney->compete_cost == '')?('бесплатное участие)'):('участие платное - '.$tourney->compete_cost).' на команду)')); ?>
                      </p>
                      <p class="author_par">
                        Добавлено <?= (new DateTime($tourney->date_added))->format('d.m.Y'); ?>
                      </p>
                    </div>
                    <div class="col-xs-4 right">
                      <?= ($tourney->prise_pool != '')?(
                        '<p>Призовой фонд: <br>
                        <span class="prize show-pinfo">
                        '.$tourney->prise_pool.'
                      </span></p>'):('') ?>

                      <div class="prize-info">
                        <?= $tourney->prise_info ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <?php $istheretourneys = true; ?>
            <?php endforeach; 
            if($istheretourneys !== true){
              echo "<p class='author_par full-width' align='center'>Турниры не найдены</p>";
            };
            ?>
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
          </div>
          <div class="side_bar col-md-4 hidden-sm hidden-xs">
            <?=VkWidget::widget();?>
          </div>
        </div>
        <div> <!-- TEMPLATE FIX-->