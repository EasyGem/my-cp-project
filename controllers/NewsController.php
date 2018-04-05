<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\News;

class NewsController extends Controller
{
  public function actionIndex()
  {
    $query = News::find()->where('ready = 1');

    $twoWeeksAgo = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 14, date('Y')));

    $lastNews = $query->orderBy('date DESC')
    ->limit(3)
    ->all();

    $lastNews = $query->orderBy('date DESC')
    ->limit(3)
    ->all();

    $topNews = $query->orderBy('views DESC')
    ->where('`date` > "' .$twoWeeksAgo. '"')
    ->limit(4)
    ->all();

    function GameNews($game, $queryFunc) {
      return $queryFunc->orderBy('date DESC')
      ->where('`game` = "'.$game.'"')
      ->limit(5)
      ->all();
    };

    $gameNews = [
      GameNews('Dota 2', $query),
      GameNews('CSGO', $query),
      GameNews('LoL', $query),
      GameNews('Hearthstone', $query)
    ];


    return $this->render('index', [
      'lastNews' => $lastNews,
      'topNews' => $topNews,
      'gameNews' => $gameNews,
    ]);
  }

  public function actionNewPage($id)
  {
    if(empty($id)) return $this->goHome();
    $query = News::find();

    $twoWeeksAgo = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 14, date('Y')));

    $id = str_replace(')', '', $id);
    $new = $query->where("ready = 1 and (id = $id)")->all();

    $gameNews = $query->orderBy('date DESC')
    ->where('ready = 1 and game = "'.$new[0]->game.'"')
    ->limit(3)
    ->all();

    $topNews = $query->orderBy('views DESC')
    ->where('ready = 1 and `type` = "general" '. 'and `date` > "' .$twoWeeksAgo. '"')
    ->limit(3)
    ->all();

    if(isset($new[0]->ready) and $new[0]->ready != 0)
    {
      return $this->render('new-page', [
        'new' => $new,
        'gameNews' => $gameNews,
        'topNews' => $topNews
      ]);
    }else{
      return $this->render('error', [
      ]);
    };
  }
}
