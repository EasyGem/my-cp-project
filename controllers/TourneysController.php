<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\EntryForm;
use yii\filters\VerbFilter;
use app\models\Tourneys;
use app\models\Games;
use yii\data\Pagination;
use app\models\TourneyOptions;

class TourneysController extends Controller
{
    public function actionIndex($game='')
    {
        $tddate = date('Y-m-d H-i-s');
        $gDesc = "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et fuga qui pariatur in placeat eaque exercitationem voluptatibus veniam. Reiciendis, quod.";
        $gTitle = 'Киберспортивные турниры';
        $gImage = "bg.png";
        if (!empty($game) or !empty($_GET['TourneyOptions']['game'])) {
            $game =(empty($game))?($_GET['TourneyOptions']['game']):($game);
            $gamesQ = Games::find()
            ->all();
            switch ($game) {
                case "dota":
                $gId = 0;
                break;
                case "cs":
                $gId = 1;
                break;
                case "lol":
                $gId = 2;
                break;
                case "hs":
                $gId = 3;
                break;
            };
            if (isset($gId)) {
                $gDesc = $gamesQ[$gId]->game_desc;
                $gTitle = $gamesQ[$gId]->title;
                $gImage = $gamesQ[$gId]->image;
                $gameQ = " and `discipline` = '". $gamesQ[$gId]->name ."'";
            };
        };
        
        if(!isset($_GET['TourneyOptions'])) {


        $query = Tourneys::find()->where("`status` = 1 and (`date_event` > '".$tddate."' or 
            (`date_event` = '".$tddate."'))".$gameQ);

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        
        $tourneys = $query->orderBy('date_event')
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        
        $town = (new \yii\db\Query())
        ->select('`town`')
        ->from('tourneys')
        ->where('`status` = 1'.$gameQ)
        ->groupby('`town`')
        ->all();
        
        $model = new TourneyOptions();
        
        return $this->render('index', [
            'tourneys' => $tourneys,
            'pagination' => $pagination,
            'model' => $model,
            'tddate' => $tddate,
            'town' => $town,
            'gTitle' => $gTitle,
            'gDesc' => $gDesc,
            'gImage' => $gImage,
        ]);
    } else {
        //SEARCHING 

        $town = (new \yii\db\Query())
        ->select('`town`')
        ->from('tourneys')
        ->where('`status` = 1'.$gameQ)
        ->groupby('`town`')
        ->all();

        $query = Tourneys::find();

        $textForRequest = '';

        if(($_GET['TourneyOptions']['type']) != 'all' and !empty($_GET['TourneyOptions']['type'])){
            if($_GET['TourneyOptions']['type'] == 'virtual') $type = 'virtual';
            if($_GET['TourneyOptions']['type'] == 'local') {
                if(isset($_GET['TourneyOptions']['town']) and $_GET['TourneyOptions']['town'] != ""){
                  $textForRequest = $textForRequest." and `town` = '".$_GET['TourneyOptions']['town']."'";
              };
              $type = 'local';
          };
          $textForRequest = $textForRequest." and (`type` = '".$type."') ";
      };
      if(isset($_GET['TourneyOptions']['search_text']) and $_GET['TourneyOptions']['search_text'] != ""){
          $textForRequest = $textForRequest." and (`title` LIKE '%".$_GET['TourneyOptions']['search_text']."%' or `text` LIKE '%".$_GET['TourneyOptions']['search_text']."%' 
          or `organisator` LIKE '%".$_GET['TourneyOptions']['search_text']."%') ";
      };

      if(isset($_GET['TourneyOptions']['tense']) and $_GET['TourneyOptions']['tense'] == "past"){
          $textForRequest = $textForRequest." and `date_last_day_event` < '".$tddate."'";
      }elseif(isset($_GET['TourneyOptions']['tense']) and $_GET['TourneyOptions']['tense'] == "pres"){
          $textForRequest = $textForRequest." and `date_event` <= '".$tddate."' and `date_last_day_event` >= '".$tddate."'";
      }elseif(isset($_GET['TourneyOptions']['tense']) and $_GET['TourneyOptions']['tense'] == "fut"){
          $textForRequest = $textForRequest." and `date_event` > '".$tddate."'";
      };

      if(isset($_GET['TourneyOptions']['status']) and $_GET['TourneyOptions']['status'] != ""){
          if(in_array('team', $_GET['TourneyOptions']['status'])){
            $textForRequest = $textForRequest." and `date_last_day_reg` > '".$tddate."'";
            $textForRequest = $textForRequest." and `team_status` = 1";
        };
        if(in_array('free', $_GET['TourneyOptions']['status'])){
            $textForRequest = $textForRequest." and `compete_cost` = ''";
        };
    };     
    $tourneys = $query->orderBy('date_added DESC')
    ->where("`status` = 1 $textForRequest".$gameQ);

    $pagination = new Pagination([
        'defaultPageSize' => 10,
        'totalCount' => count($tourneys),
    ]);

        // Again, because first time was without pagination
    $tourneys = $tourneys
    ->offset($pagination->offset)
    ->limit($pagination->limit)
    ->all();

    $model = new TourneyOptions();

    return $this->render('index', [
        'tourneys' => $tourneys,
        'pagination' => $pagination,
        'model' => $model,
        'tddate' => $tddate,
        'town' => $town,
        'gTitle' => $gTitle,
        'gDesc' => $gDesc,
        'gImage' => $gImage,
    ]);
};
}
public function actionTourneyPage($id)
{
  if(empty($id)) return $this->goHome();

  $query = Tourneys::find();

  $tourney = $query->where("id = $id")
  ->all();


  if(isset($tourney[0]->status) and $tourney[0]->status = 1)
  {
    $tddate = (new \yii\db\Query())
    ->select(['CURDATE()'])
    ->all();
    return $this->render('tourney-page', [
        'tourney' => $tourney,
        'tddate' => $tddate
    ]);
}else{
    return $this->render('error', [
    ]); 
};
}

}
