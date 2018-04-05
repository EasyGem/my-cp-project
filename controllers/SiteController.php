<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\LoginForm;
use app\assets\AppAsset;
use yii\web\User;
use app\models\NewsAddForm;
use app\models\TourneysAddForm;
use yii\db\Command;
use app\models\News;
use app\models\Tourneys;
use yii\data\Pagination;
use yii\db\Query;
use app\models\AddUserForm;
use app\models\RemoveUserForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
      return [
        'access' => [
          'class' => AccessControl::className(),
          'only' => ['logout'],
          'rules' => [
            [
              'actions' => ['logout'],
              'allow' => true,
              'roles' => ['@'],
            ],
          ],
        ],
        'verbs' => [
          'class' => VerbFilter::className(),
          'actions' => [
            'logout' => ['post'],
          ],
        ],
      ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {

      return [
        'error' => [
          'class' => 'yii\web\ErrorAction',
        ],
        'captcha' => [
          'class' => 'yii\captcha\CaptchaAction',
          'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
        ],
       /* 'image-upload' => [
            'class' => 'common\widgets\UploadKsl',
            'url' => 'http://localhost/web/images/', // URL адрес папки куда будут загружатся изображения.
            'path' => '@webroot/web/images/', // Или абсолютный путь к папке куда будут загружатся изображения.
            ],
        //для вставки уже загруженных изображений
        'images-get' => [
            'class' => 'vova07\imperavi\actions\GetAction',
            'url' => 'http://localhost/web/images/', // URL адрес папки где хранятся изображения.
            'path' => '@webroot/web/images/', // Или абсолютный путь к папке с изображениями.
            'type' => \vova07\imperavi\actions\GetAction::TYPE_IMAGES,
          ],*/
        ];
      }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionLogin()
    {
      if (!Yii::$app->user->isGuest) {
        return $this->goHome();
      }

      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
        return $this->goBack();
      }
      return $this->render('login', [
        'model' => $model,
      ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
      Yii::$app->user->logout();

      return $this->goHome();
    }
    
    public function actionIndex()
    {
      return $this->render('index');

    }
    

    public function actionContact()
    {
      $model = new ContactForm();

      if ($model->load(Yii::$app->request->post()) && $model->validate()) {

        $orgs = (new \yii\db\Query())
        ->select('*')
        ->from('`users`')
        ->where('`accessToken` = "organisator"')
        ->all();

        Yii::$app->mailer->compose()
        ->setTo('admin@cybpoint.ru')
        ->setFrom('admin@cybpoint.ru')
        ->setSubject('CP - '.$model->topic)
        ->setTextBody("From: ". $model->email . "\n" . $model->text)
        ->send();

        return $this->render('contact', ['model' => $model, 'orgs' => $orgs, 'message' => 'Сообщение успешно отправлено!']);
      } else {
        $orgs = (new \yii\db\Query())
        ->select('*')
        ->from('`users`')
        ->where('`accessToken` = "organisator"')
        ->all();
        return $this->render('contact', ['model' => $model, 'orgs' => $orgs]);
      }
    }

    // HELPING FUNCTIONS >>>
    public function checkAccess($access1, $access2, $access3) {
      if (Yii::$app->user->isGuest)
        {
          return false;
        } elseif (
          !(Yii::$app->user->identity->accessToken == $access1 
            or Yii::$app->user->identity->accessToken == $access2 
            or Yii::$app->user->identity->accessToken == $access3))
        {
          return false;
        };
        return true;
      }

      public function isThere($id) {
        $isthere = (new \yii\db\Query())
        ->select('*')
        ->from('`news`')
        ->where("`id` =".$id)
        ->all();
        return $isthere;
      }

      public function insertNews($id, $title, $type, $game, $text, $new_desc, $date, $image, $views, $author, $ready) {
        Yii::$app->db->createCommand()
        ->insert('news', [
          'id' => $id,
          'title' => $title,
          'type' => $type,
          'game' => (!empty($game))?($game):(''),
          'text' => $text,
          'new_desc' => $new_desc,
          'date' => $date,
          'image' => (!empty($image))?($image):(''),
          'views' => $views,
          'author' => $author,
          'rights' => '',
          'ready' => $ready
        ])->execute();
      }

      public function updateNews($id, $title, $type, $game, $text, $new_desc, $image, $ready, $author, $views, $date) {
        $arr = [
          'title' => $title,
          'type' => $type,
          'game' => (!empty($game))?($game):(''),
          'text' => $text,
          'new_desc' => $new_desc,
          'views' => $views,
          'author' => $author,
          'ready' => $ready
        ];
        if (!empty($image) and $image != '') {
          $arr['image'] = $image;
        };
        Yii::$app->db->createCommand()
        ->update('news', $arr
          , '`id` ='.$id)
        ->execute();
      }
      public function updateNewsP($id, $title, $type, $game, $text, $new_desc, $date, $image, $ready, $author, $views) {
        $arr = [
          'title' => $title,
          'type' => $type,
          'game' => (!empty($game))?($game):(''),
          'text' => $text,
          'new_desc' => $new_desc,
          'views' => $views,
          'date' => $date,
          'author' => $author,
          'ready' => $ready
        ];
        if (!empty($image) and $image != '') {
          $arr['image'] = $image;
        };
        Yii::$app->db->createCommand()
        ->update('news', $arr
          , '`id` ='.$id)
        ->execute();
      }

      public function updateNewsEdit($id, $title, $type, $game, $text, $new_desc, $date, $image) {
        $arr = [
          'title' => $title,
          'type' => $type,
          'game' => (!empty($game))?($game):(''),
          'text' => $text,
          'new_desc' => $new_desc,
          'date' => $date,
        ];
        if (!empty($image) and $image != '') {
          $arr['image'] = $image;
        };
        Yii::$app->db->createCommand()
        ->update('news', $arr
          , '`id` ='.$id)
        ->execute();
      }

      public function updateNewsEditP($id, $title, $type, $game, $text, $new_desc, $date, $image, $ready) {
        $arr = [
          'title' => $title,
          'type' => $type,
          'game' => (!empty($game))?($game):(''),
          'text' => $text,
          'new_desc' => $new_desc,
          'date' => $date,
        ];
        if (!empty($image) and $image != '') {
          $arr['image'] = $image;
        };
        $arr['ready'] = ($ready == 1)?(1):(0);
        Yii::$app->db->createCommand()
        ->update('news', $arr
          , '`id` ='.$id)
        ->execute();
      }

      public function checkRightsTourney($tourney){ 
        if((Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "tourneysmoderator") 
          or strripos($tourney[0]['rights'], Yii::$app->user->identity->username) == true 
          or $tourney[0]['rights'] == Yii::$app->user->identity->username 
          or $tourney[0]['organisator'] == Yii::$app->user->identity->username){
          return true;
        }else{
          return false;
        }
      }

      public function checkRightsNew($newID){ 
        $new_to_check = (new \yii\db\Query())
        ->select('*')
        ->from('`news`')
        ->where("`id` =".$newID)
        ->all();
        if(Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "newsmoderator"
          or strripos($new_to_check[0]['rights'], Yii::$app->user->identity->username) == true 
          or $new_to_check[0]['rights'] == Yii::$app->user->identity->username 
          or $new_to_check[0]['author'] == Yii::$app->user->identity->username){
          return true;
        }else{
          return false;
        }
      }
      // <<< HELPING FUNCTIONS

      public function actionNewsPanel(){
        if(!($this->checkAccess('admin', 'newsmoderator', 'newswriter'))) {
          return $this->render('error', 
            [
              'message' => 'Not Found (#404)', 
              'name' => 'Page not found.'
            ]);
        };
        if (!isset($_GET['part'])) 
          return $this->render('news-panel');

        if($_GET['part'] == 'add')
        {
          $model = new NewsAddForm();

          if ($model->load(Yii::$app->request->post()))
            {
              if ($model->validate())
              {            
                if($model->method == 'save')
                {
                  $model->file = UploadedFile::getInstance($model, 'file');
                  $model->upload();
                    // file is uploaded successfully

                  $isthere = $this->isThere($model->id);

                  if($isthere == [])
                  {
                    //newsData
                    $image = ($model->file != '')?
                    'uploaded-files/news/'.'main-id' . $model->id . '.' . $model->file->extension:
                    NULL;
                    $model->image = $image;

                    $id = $model->id;
                    $title = $model->title;
                    $type = $model->type;
                    $game = $model->game;
                    $text = $model->text;
                    $new_desc = $model->new_desc;
                    $date = date('Y-m-d H:i:s');
                    $views = 0;
                    $author = Yii::$app->user->identity->username;
                    
                    $ready = -1;

                    $this->insertNews($id, $title, $type, $game, $text, $new_desc, $date, $image, $views, $author, $ready);
                    
                    //CREATED AND SAVED
                    return $this->render('news-panel-add', [
                      'model' => $model, 
                      'message' => 'Запись успешно сохранена!'
                    ]);
                  } else {
                    $id = $model->id;
                    $title = $model->title;
                    $type = $model->type;
                    $game = $model->game;
                    $text = $model->text;
                    $date = date('Y-m-d H:i:s');
                    $new_desc = $model->new_desc;

                    $model->image = ($model->file == '')?
                    (str_replace('%20', ' ', $isthere[0]['image'])):
                    ('uploaded-files/news/' . 'main-id' . $id . '.' . $model->file->extension);

                    if(!($this->checkRightsNew($model->id)))
                      return $this->render('news-panel-add', [
                        'model' => $model, 
                        'message' => 'При обновлении данных произошла ошибка.', 
                        'alerttype' => 'danger'
                      ]);

                    $image = ($model->file != '')?'uploaded-files/news/'.'main-id' . $id . '.' . $model->file->extension:$isthere[0]['image'];

                    $this->updateNews($id, $title, $type, $game, $text, $new_desc, $image, $ready, $author, $views, $date);
                    
                     //REPLACED AND SAVED
                    return $this->render('news-panel-add', [
                      'model' => $model, 
                      'message' => 'Запись успешно сохранена!'
                    ]);
                  };
                } elseif ($model->method == 'test')
                {                
                  return $this->render('news-panel-test', [
                    'model' => $model, 
                  ]);
                } elseif ($model->method == 'publish')
                {
                  $isthere = $this->isThere($model->id);

                  $model->file = UploadedFile::getInstance($model, 'file');
                  $model->upload();

                  if($isthere == [])
                  {
                  //news data
                    $id = $model->id;
                    $title = $model->title;
                    $type = $model->type;
                    $game = $model->game;
                    $text = $model->text;
                    $new_desc = $model->new_desc;
                    $date = date('Y-m-d H:i:s');
                    $views = 0;
                    $author = Yii::$app->user->identity->username;
                    $ready = 0;

                    $model->image = ($model->file == '')?
                    (''):
                    ('uploaded-files/news/' . 'main-id' . $id . '.' . $model->file->extension);
                    $image = ($model->file != '')?
                    ('uploaded-files/news/'.'main-id' . $id . '.' . $model->file->extension):
                    (NULL);

                    $this->insertNews($id, $title, $type, $game, $text, $new_desc, $date, $image, $views, $author, $ready);
                //CREATED AND SAVED
                    return $this->render('news-panel-add', [
                      'model' => $model, 
                      'message' => 'Запись успешно отправлена на модерацию!'
                    ]);
                  }else{

                    if(!$this->checkRightsNew($model->id))
                      return $this->render('news-panel-add', [
                        'model' => $model, 
                        'message' => 'При обновлении данных произошла ошибка.', 
                        'alerttype' => 'danger'
                      ]);

                    $id = $model->id;
                    $title = $model->title;
                    $type = $model->type;
                    $game = $model->game;
                    $text = $model->text;
                    $new_desc = $model->new_desc;
                    $date = date('Y-m-d H:i:s');
                    $views = 0;
                    $author = Yii::$app->user->identity->username;

                    $ready = 0;

                    $model->image = ($model->file == '')?
                    (str_replace('%20', ' ', $isthere[0]['image'])):
                    ('uploaded-files/news/' . 'main-id' . $id . '.' . $model->file->extension);
                    $image = ($model->file != '')?
                    ('uploaded-files/news/'.'main-id' . $id . '.' . $model->file->extension):
                    ($isthere[0]['image']);

                    $this->updateNewsP($id, $title, $type, $game, $text, $new_desc, $date, $image, $ready, $author, $views);
                 //REPLACED AND SAVED
                    return $this->render('news-panel-add', [
                      'model' => $model, 
                      'message' => 'Запись успешно отправлена на модерацию!'
                    ]);
                  };
                };
              };
            };

            $idp = (new \yii\db\Query())
            ->select('`id`')
            ->from('`id`')
            ->orderby('`id` DESC')
            ->limit('1')
            ->all();
            $id = $idp[0]['id'] + 1;

            Yii::$app->db->createCommand()
            ->insert('id', ['id' => $id])->execute();

            return $this->render('news-panel-'.$_GET['part'], [
              'model' => $model,
              'id' => $id,
            ]);
          } elseif ($_GET['part'] == 'edit') //EDIT
          {
            $message = '';
            $myQ = '';
            if($_GET['rights'] == 'my'){
              $message = "Отображены все записи, к редактированию и предпросмотру которых вы имеете доступ.";
              $myQ = ("`author` = '".Yii::$app->user->identity->username . "' 
                or `rights` LIKE '%". Yii::$app->user->identity->username . "%' or `rights` = '".Yii::$app->user->identity->username . "'");
            };

            $query = News::find()->where($myQ);
            $pagination = new Pagination([
              'defaultPageSize' => 10,
              'totalCount' => $query->count()
            ]);

            $getNews = $query->orderBy('date DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

            return $this->render('news-panel-'.$_GET['part'], [
              'pagination' => $pagination,
              'news' => $getNews,
              'message' => $message
            ]);
         } elseif ($_GET['part'] == 'authors' ) //AUTHORS
         {
          $authors = (new \yii\db\Query())
          ->select('`author`')
          ->from('`news`')
          ->groupby('author')
          ->all();

          $i = 0;

          foreach($authors as $author){
            $newsnum[$i] = (new \yii\db\Query())
            ->select('COUNT(0)')
            ->from('`news`')
            ->where("`author` = '".$author['author']."'")
            ->all();
            $i++;
          };

          return $this->render('news-panel-'.$_GET['part'], [
            'users' => $authors, 
            'newsnum' => $newsnum
          ]);
        };
      }

      public function actionNewEditor($id){
        if(!($this->checkAccess('admin', 'newsmoderator', 'newswriter'))) {
          return $this->render('error', 
            [
              'message' => 'Not Found (#404)', 
              'name' => 'Page not found.'
            ]);
        };
        $model = new NewsAddForm();
        if ($model->load(Yii::$app->request->post()))
         {
           if ($model->validate())
           {
            if($model->method == 'test')
            {
              if(!$this->checkRightsNew($model->id))
                return $this->render('news-panel-add', ['model' => $model, 
                  'message' => 'При обновлении данных произошла ошибка.', 'alerttype' => 'danger']);

              return $this->render('news-panel-test', [
                'model' => $model
              ]);
            }elseif($model->method == 'save')
            {
              if(!$this->checkRightsNew($model->id))
                return $this->render('news-panel-add', ['model' => $model, 
                  'message' => 'При обновлении данных произошла ошибка. Вы не обладаете правами на редактирование этой записи. ', 'alerttype' => 'danger']);

              $model->file = UploadedFile::getInstance($model, 'file');
              $model->upload();

              $image = ($model->file != '')?
              'uploaded-files/news/'.'main-id' . $model->id . '.' . $model->file->extension:
              NULL;
              $model->image = $image;

              $id = $model->id;
              $title = $model->title;
              $type = $model->type;
              $game = $model->game;
              $text = $model->text;
              $new_desc = $model->new_desc;
              $date = date('Y-m-d H:i:s'); 

              $this->updateNewsEdit($id, $title, $type, $game, $text, $new_desc, $date, $image, $ready);

                 //REPLACED AND SAVED
              return $this->render('news-panel-add', ['model' => $model, 'message' => 'Запись успешно сохранена!']);
            }elseif($model->method == 'publish')
            {
            //***************
              if(!$this->checkRightsNew($model->id))
                return $this->render('news-panel-add', ['model' => $model, 
                  'message' => 'При обновлении данных произошла ошибка.', 'alerttype' => 'danger']);

              $model->file = UploadedFile::getInstance($model, 'file');
              $model->upload();  

              $image = ($model->file != '')?
              'uploaded-files/news/'.'main-id' . $model->id . '.' . $model->file->extension:
              NULL;
              $model->image = $image;

              $new = (new \yii\db\Query())
              ->select('ready')
              ->from('`news`')
              ->where("`id` = ".$model->id)
              ->all();

              $id = $model->id;
              $title = $model->title;
              $type = $model->type;
              $game = $model->game;
              $text = $model->text;
              $new_desc = $model->new_desc;
              $date = date('Y-m-d H:i:s');
              $ready = $new[0]['ready']; 

              $this->updateNewsEditP($id, $title, $type, $game, $text, $new_desc, $date, $image, $ready);

            //REPLACED AND SAVED
              return $this->render('new-edit', ['model' => $model, 'message' => 'Запись успешно обновлена!']);
          //***************
            };
          };
        };
        $query = News::find();
        if(!isset($id)){
          $errname = 'Страница не найдена.'; 
          $errmessage = 'ID указан неверно.';
          return $this->render('error', ['name' => $errname, 'message' => $errmessage]);
        };

        $new = $query->where('`id` = '.$id)
        ->all();

        if(!isset($new[0]->title)){
          $errname = 'Страница не найдена.'; 
          $errmessage = 'Заправшиваемая страница не найдена.';
          return $this->render('error', ['name' => $errname, 'message' => $errmessage]);
        }
        if(!((Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "newsmoderator") 
          or strripos($new[0]->rights, Yii::$app->user->identity->username) == true or $new[0]->rights == Yii::$app->user->identity->username 
          or $new[0]->author == Yii::$app->user->identity->username)){
            $errname = 'Нет прав для просмотра страницы.'; 
            $errmessage = 'Вы не обладаете необходимыми правами для просмотра этой страницы.';
          };

          $model = new NewsAddForm();
          $model['text'] = $new[0]['text'];
          $model['image'] = $new[0]['image'];
          $model['title'] = $new[0]['title'];
          $model['id'] = $new[0]['id'];
          $model['type'] = $new[0]['type'];
          $model['game'] = $new[0]['game'];
          $model['new_desc'] = $new[0]['new_desc'];
          return $this->render('new-edit', [
            'model' => $model
          ]);
        }



        public function actionSitePanel(){
          if(Yii::$app->user->isGuest){
            return $this->render('error', ['name' => 'Not Found (#404)', 'message' => 'Page not found.']);
          }elseif(Yii::$app->user->identity->accessToken == 'admin'){
            $info['lastnew'] = (new Query())
            ->select('`title`, `id`')
            ->from('`news`')
            ->orderby('`date` DESC')
            ->limit(1)
            ->all();
            if(!isset($info['lastnew'][0])){
              $info['lastnew'][0]['id'] = 0;
              $info['lastnew'][0]['title'] = 'Отсутствует';
            };
            $info['lasttourney'] = (new Query())
            ->select('`title`, `id`')
            ->from('`tourneys`')
            ->orderby('`date_actual_added` DESC')
            ->limit(1)
            ->all();
            if(!isset($info['lasttourney'][0])){
              $info['lasttourney'][0]['id'] = 0;
              $info['lasttourney'][0]['title'] = 'Отсутствует';
            };

            $month = (date('m') - 1);
            $month = ($month > 9)?($month):(0 . $month);
            $dateMonthAgo = date('Y-'.$month.'-d');

            if(isset($_GET['part']))
            {
              if($_GET['part'] == 'users'){
                $users = (new Query())
                ->select('*')
                ->from('`users`')
                ->all();

                $i = 0;
                foreach($users as $user){
                  $usersNT[$i]['N'] = (new Query())
                  ->select('COUNT(0)')
                  ->from('`news`')
                  ->where('`ready` = 1 and `author` = "'.$user['username'].'"')
                  ->all();

                  $usersNT[$i]['T'] = (new Query())
                  ->select('COUNT(0)')
                  ->from('`tourneys`')
                  ->where('`status` = 1 and (`rights` = "'.$user['username'] .'" 
                    or `organisator` = "'.$user['username'] .'" or `rights` LIKE "%'.$user['username'] .'%")')
                  ->all();

                  $i++;
                };
                unset($i);
                return $this->render('site-panel-users', ['users' => $users, 'usersNT' => $usersNT]);
              };
              return $this->render('site-panel', ['info' => $info]);
            //********************ACTIONS HERE*********************
            }elseif(isset($_GET['action'])){
              if($_GET['action'] == 'adduser'){
                $access = (new Query())
                ->select('`accessToken`')
                ->from('`users`')
                ->groupby('`accessToken`')
                ->all();

                $model = new AddUserForm();
                if ($model->load(Yii::$app->request->post()))
                  {
                    if ($model->validate())
                    {
                      if($model->rep_password == $model->password){
                        $id = (new Query())
                        ->select('`id`')
                        ->from('`users`')
                        ->orderby('`id` DESC')
                        ->limit(1)
                        ->all();

                        $id = $id[0]['id']+1;
                        $username = $model->username;
                        $password = $model->password;
                        $accessToken = $model->accessToken;
                        $contact_link = (isset($model->contact_link))?($model->contact_link):(NULL);

                        Yii::$app->db->createCommand()
                        ->insert('users', [
                          'id' => $id,
                          'username' => $username,
                          'password' => $password,
                          'authKey' => $username.$id.(date('z') * date('s')),
                          'accessToken' => $accessToken,
                          'contact_link' => $contact_link,
                        ])->execute();
                        return $this->render('site-panel-adduser', ['access' => $access, 'message' => 'Пользователь '.$username.' добавлен.', 'model' => $model]);
                      }else{
                        return $this->render('site-panel-adduser', ['access' => $access, 'message' => 'Пароли не совпадают.', 'alerttype' => 'danger', 'model' => $model]);
                      };
                    };
                  };
                  return $this->render('site-panel-adduser', ['model' => $model, 'access' => $access]);
                }elseif($_GET['action'] == 'removeuser'){
                  $users = (new Query())
                  ->select('*')
                  ->from('`users`')
                  ->where("`accessToken` != 'admin'")
                  ->groupby('`username`')
                  ->all();
                  $model = new RemoveUserForm();
                  if ($model->load(Yii::$app->request->post()))
                    {
                      if ($model->validate())
                      {
                        if(Yii::$app->user->identity->password == $model->password){
                          $i = 0;
                          $thereis = 0;
                          while(isset($users[$i])){
                            if($users[$i]['id'] == $model->username){
                              Yii::$app->db->createCommand('DELETE FROM `users` WHERE `users`.`id` = '. $model->username)
                              ->execute();
                              $model->username = false;
                              return $this->render('site-panel-removeuser', ['model' => $model, 'message' => 'Пользователь успешно удален.', 'users' => $users]);
                            };
                            $i++;
                          };
                          unset($i);
                          $model->username = false;
                          return $this->render('site-panel-removeuser', ['model' => $model, 'message' => 'Невозможно удалить пользователя.', 'alerttype' => 'danger', 'users' => $users]);
                        }else{
                          return $this->render('site-panel-removeuser', ['model' => $model, 'message' => 'Неверный пароль.', 'alerttype' => 'warning', 'users' => $users]);
                        };
                      }
                    };
                    return $this->render('site-panel-removeuser', ['model' => $model, 'users' => $users]);
                  };
                  return $this->render('site-panel', ['info' => $info]);
                }else{
                 return $this->render('site-panel', ['info' => $info]);
               };
             }else{
              return $this->render('error', ['message' => 'Not Found (#404)', 'name' => 'Page not found.']);
            };
          }
    //admin panel 
          public function actionAdminPanel(){
            if(Yii::$app->user->isGuest){
              return $this->render('error', ['name' => 'Not Found (#404)', 'message' => 'Page not found.']);
            }elseif(Yii::$app->user->identity->accessToken == 'admin'){
             return $this->render('admin-panel');
           }else{
            return $this->render('error', ['message' => 'Not Found (#404)', 'name' => 'Page not found.']);
          };
        }

    //*******************************NEWS PANEL START************************************
        public function actionNewPreview($id){
          $query = News::find();
          if(isset($id)){
            $new = $query->where('`id` = '.$id)
            ->all();
            if(isset($new[0]->title) and isset(Yii::$app->user->identity->accessToken)){
             if((Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "newsmoderator") 
              or strripos($new[0]->rights, Yii::$app->user->identity->username) == true or $new[0]->rights == Yii::$app->user->identity->username 
              or $new[0]->author == Yii::$app->user->identity->username){
              $imgUrl = $new[0]['image'];
              return $this->render('news-panel-test', [
                'model' => $new[0],
                'imgUrl' => $imgUrl
              ]);
            }else{
              $errname = 'Нет прав для просмотра страницы.'; 
              $errmessage = 'Вы не обладаете необходимыми правами для просмотра этой страницы.';
            };
          }else{
            $errname = 'Страница не найдена.'; 
            $errmessage = 'Заправшиваемая страница не найдена.';
          };
        }else{
          $errname = 'Страница не найдена.'; 
          $errmessage = 'ID указан неверно.';
        };
        return $this->render('error', ['name' => $errname, 'message' => $errmessage]);
      }

      public function actionChangeStatus($id, $action){
        if(!(Yii::$app->user->isGuest) and (Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "newsmoderator")){
          if(isset($id) and isset($action)){
            if($action == 'approve'){
              $ready = 1;
              $message = "Новость одобрена.";
            }elseif($action == 'disapprove'){
              $ready = -1;
              $message = "Новость отколнена.";
            };
            Yii::$app->db->createCommand()
            ->update('news', 
              [
                'ready' => $ready
              ], '`id` ='.$id)
            ->execute();

            $query = News::find();
            $pagination = new Pagination([
              'defaultPageSize' => 10,
              'totalCount' => $query->count(),
            ]);

            $getNews = $query->orderBy('date DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

            return $this->render('news-panel-edit', [
              'message' => $message,
              'pagination' => $pagination,
              'news' => $getNews]);
          }else{
            return $this->render('error', ['message' => 'ID или Action указаны неверно.', 'name' => 'Страница не найдена.']);
          };
        }else{
          return $this->render('error', ['message' => 'Вы не обладаете необходимыми правами для этого действия.', 'name' => 'Недостаточно прав.']);
        };
      }

    //NEWS PANEL END



    //*********************TOURNEYS PANEL START********************************************

      public function actionTourneysPanel(){
        if(Yii::$app->user->isGuest){
          return $this->render('error', ['name' => 'Not Found (#404)', 'message' => 'Page not found.']);
        }elseif(Yii::$app->user->identity->accessToken == 'admin' or Yii::$app->user->identity->accessToken == 'tourneysmoderator' or Yii::$app->user->identity->accessToken == 'organisator')
        {
          if(isset($_GET['part']))
          {
            if($_GET['part'] == 'add')
            {
              $model = new TourneysAddForm();

              if ($model->load(Yii::$app->request->post()))
                {
                  if ($model->validate())
                  {
                    if($model->method == 'save')
                    {
                      $model->file = UploadedFile::getInstance($model, 'file');
                      $model->upload();

                      $isthere = (new \yii\db\Query())
                      ->select('`id`')
                      ->from('`tourneys`')
                      ->where("`id` =".$model->id)
                      ->all();

                      $dis = $model->discipline;
                      $res = $model->discipline;
                      $model->discipline = '';
                      $a = 0;
                      $image = $model->image;
                      while(isset($dis[$a])){
                        if($a>0){
                          $dis[$a] = " | ".$dis[$a];
                        };
                        $model->discipline  = $model['discipline'].$dis[$a];
                        $a++;
                      };

                      $date_actual_added = date('Y-m-d');
                      $ready = -1;
                      $organisator = $model->organisator;
                      $text = $model->text;
                      $title = $model->title;
                      $town = $model->town;
                      $desc_visitors = $model->desc_visitors;
                      $desc_players = $model->desc_players;
                      $location = $model->location;
                      $date_event = $model->date_event; 
                      $date_last_day_event = ($model->date_last_day_event == '')?$date_event:$model->date_last_day_event;
                      $ticket_cost = $model->ticket_cost; 
                      $compete_cost = ($model->compete_cost == '')?NULL:$model->compete_cost;
                      $buy_ticket_link = ($model->buy_ticket_link == '')?NULL:$model->buy_ticket_link;
                      $reg_team_link = ($model->reg_team_link == '')?NULL:$model->reg_team_link;
                      $contact = ($model->contact == '')?NULL:$model->contact;
                      $discipline = $model->discipline; 
                      $team_status = $model->team_status;
                      $organisator = $model->organisator;
                      $translation = ($model->translation == '')?NULL:$model->translation;

                      if($isthere == [])
                      {
                       $image = ($model->file != '')?'uploaded-files/tourneys/'.'main-id' . $model->id . '.' . $model->file->extension:NULL;
                       $model->image = ($model->file == '')?(''):('uploaded-files/tourneys/' . 'main-id' . $model->id . '.' . $model->file->extension);

                       Yii::$app->db->createCommand()
                       ->insert('tourneys', [
                        'id' => $model->id, 
                        'title' => $title, 
                        'town' => $town, 
                        'text' => $text, 
                        'desc_visitors' => $desc_visitors, 
                        'desc_players' => $desc_players, 
                        'location' => $location, 
                        'date_event' => $date_event, 
                        'date_last_day_event' => $date_last_day_event, 
                        'date_actual_added' => $date_actual_added, 
                        'ticket_cost' => $ticket_cost, 
                        'compete_cost' => $compete_cost, 
                        'buy_ticket_link' => $buy_ticket_link, 
                        'reg_team_link' => $reg_team_link, 
                        'organisator' => $organisator, 
                        'contact' => $contact, 
                        'discipline' => $discipline, 
                        'team_status' => $team_status, 
                        'image' => $image, 
                        'translation' => $translation, 
                        'status' => -1,
                        'rights' => Yii::$app->user->identity->username
                      ])
                       ->execute();

                //CREATED AND SAVED
                       $model->discipline = $dis;
                     }else{
                      $tourney_to_check = (new \yii\db\Query())
                      ->select('*')
                      ->from('`tourneys`')
                      ->where("`id` =".$model->id)
                      ->all();

                      $model->image = ($model->file == '')?(str_replace('%20', ' ', $tourney_to_check[0]['image'])):('uploaded-files/tourneys/' . 'main-id' . $model->id . '.' . $model->file->extension);
                      $image = ($model->file != '')?'uploaded-files/tourneys/'.'main-id' . $model->id . '.' . $model->file->extension:$tourney_to_check[0]['image'];


                      if($this->checkRightsTourney($tourney_to_check) == true){

                        Yii::$app->db->createCommand()
                        ->update('tourneys', [
                          'title' => $title, 
                          'town' => $town, 
                          'text' => $text, 
                          'desc_visitors' => $desc_visitors, 
                          'desc_players' => $desc_players, 
                          'location' => $location, 
                          'date_event' => $date_event, 
                          'date_last_day_event' => $date_last_day_event, 
                          'ticket_cost' => $ticket_cost, 
                          'compete_cost' => $compete_cost, 
                          'buy_ticket_link' => $buy_ticket_link, 
                          'reg_team_link' => $reg_team_link, 
                          'organisator' => $organisator, 
                          'contact' => $contact, 
                          'discipline' => $discipline, 
                          'team_status' => $team_status, 
                          'image' => $image, 
                          'translation' => $translation, 
                        ], '`id` ='.$model->id)
                        ->execute();
                 //REPLACED AND SAVED
                        $model->discipline = $dis;
                      }else{
                        return $this->render('tourneys-panel-add', ['model' => $model, 
                          'message' => 'При обновлении данных произошла ошибка.', 'alerttype' => 'danger']);
                      };
                    }; 
                    return $this->render('tourneys-panel-add', ['model' => $model, 
                      'message' => 'Запись успешно сохранена! В будущем вы сможете вернуться к ее 
                      редактированию в разделе "Редактировать события".']);
                  }elseif($model->method == 'test')
                  {
                    $model->file = UploadedFile::getInstance($model, 'file');
                    $model->upload();
                    if($model->file != ''){
                      $imgUrl = 'uploaded-files/tourneys/'. 'main-id' . $model->id . '.' . $model->file->extension;
                    }elseif($model->image != ''){
                     $imgUrl = str_replace(' ', '%20', $model->image);
                   }else{
                    $imgUrl = '';
                  };

                  $dis = $model->discipline;
                  $model->discipline = '';
                  $a = 0;
                  while(isset($dis[$a])){
                    if($a>0){
                      $dis[$a] = " | ".$dis[$a];
                    };
                    $model->discipline  = $model['discipline'].$dis[$a];
                    $a++;
                  };

                  return $this->render('tourneys-panel-test', ['model' => $model, 'imgUrl' => $imgUrl]); 
                }elseif($model->method == 'publish')
                {
                  $isthere = (new \yii\db\Query())
                  ->select('*')
                  ->from('`tourneys`')
                  ->where("`id` =".$model->id)
                  ->all();

                  $model->file = UploadedFile::getInstance($model, 'file');
                  $model->upload();

                  $dis = $model->discipline;
                  $res = $model->discipline;
                  $model->discipline = '';
                  $a = 0;
                  while(isset($dis[$a])){
                    if($a>0){
                      $dis[$a] = " | ".$dis[$a];
                    };
                    $model->discipline  = $model['discipline'].$dis[$a];
                    $a++;
                  };

                  $date_actual_added = date('Y-m-d');
                  $ready = 0;
                  $organisator = $model->organisator;
                  $text = $model->text;
                  $title = $model->title;
                  $image = ($model->image == '')?NULL:$model->image;
                  $town = $model->town;
                  $desc_visitors = $model->desc_visitors;
                  $desc_players = $model->desc_players;
                  $location = $model->location;
                  $date_event = $model->date_event; 
                  $date_last_day_event = ($model->date_last_day_event == '')?$date_event:$model->date_last_day_event;
                  $ticket_cost = $model->ticket_cost; 
                  $compete_cost = ($model->compete_cost == '')?NULL:$model->compete_cost;
                  $buy_ticket_link = ($model->buy_ticket_link == '')?NULL:$model->buy_ticket_link;
                  $reg_team_link = ($model->reg_team_link == '')?NULL:$model->reg_team_link;
                  $contact = ($model->contact == '')?NULL:$model->contact;
                  $discipline = $model->discipline; 
                  $team_status = $model->team_status;
                  $organisator = $model->organisator;
                  $translation = ($model->translation == '')?NULL:$model->translation;

                  if($isthere == [])
                  {
                    $model->image = ($model->file == '')?(''):('uploaded-files/tourneys/' . 'main-id' . $model->id . '.' . $model->file->extension);
                    $image = ($model->file != '')?'uploaded-files/tourneys/'. 'main-id' . $model->id . '.' . $model->file->extension:NULL;

                    Yii::$app->db->createCommand()
                    ->insert('tourneys', [
                      'id' => $model->id, 
                      'title' => $title, 
                      'town' => $town, 
                      'text' => $text, 
                      'desc_visitors' => $desc_visitors, 
                      'desc_players' => $desc_players, 
                      'location' => $location, 
                      'date_event' => $date_event, 
                      'date_last_day_event' => $date_last_day_event, 
                      'date_actual_added' => $date_actual_added, 
                      'ticket_cost' => $ticket_cost, 
                      'compete_cost' => $compete_cost, 
                      'buy_ticket_link' => $buy_ticket_link, 
                      'reg_team_link' => $reg_team_link, 
                      'organisator' => $organisator, 
                      'contact' => $contact, 
                      'discipline' => $discipline, 
                      'team_status' => $team_status, 
                      'image' => $image, 
                      'translation' => $translation, 
                      'status' => $ready,
                      'rights' => Yii::$app->user->identity->username
                    ])
                    ->execute();
                //CREATED AND SAVED
                    $model->discipline = $dis;

                  }else{
                    $tourney_to_check = (new \yii\db\Query())
                    ->select('*')
                    ->from('`tourneys`')
                    ->where("`id` =".$model->id)
                    ->all();

                    if($this->checkRightsTourney($tourney_to_check) == true){
                      $image = ($model->file != '')?'uploaded-files/news/'. 'main-id' . $model->id . '.' . $model->file->extension:$isthere[0]['image'];
                      $model->image = ($model->file == '')?(str_replace('%20', ' ', $isthere[0]['image'])):('uploaded-files/tourneys/' . 'main-id' . $model->id . '.' . $model->file->extension);
                      Yii::$app->db->createCommand()
                      ->update('tourneys', [
                        'title' => $title, 
                        'town' => $town, 
                        'text' => $text, 
                        'desc_visitors' => $desc_visitors, 
                        'desc_players' => $desc_players, 
                        'location' => $location, 
                        'date_event' => $date_event, 
                        'date_last_day_event' => $date_last_day_event, 
                        'ticket_cost' => $ticket_cost, 
                        'compete_cost' => $compete_cost, 
                        'buy_ticket_link' => $buy_ticket_link, 
                        'reg_team_link' => $reg_team_link, 
                        'organisator' => $organisator, 
                        'contact' => $contact, 
                        'discipline' => $discipline, 
                        'team_status' => $team_status, 
                        'image' => $image, 
                        'translation' => $translation, 
                        'status' => $ready
                      ], '`id` ='.$model->id)
                      ->execute();
                 //REPLACED AND SAVED
                      $model->discipline = $dis;
                    }else{
                      return $this->render('tourneys-panel-add', ['model' => $model, 
                        'message' => 'При обновлении данных произошла ошибка.', 'alerttype' => 'danger']);
                    };
                  };
                  return $this->render('tourneys-panel-add', 
                    ['model' => $model, 'message' => 'Запись успешно отправлена на модерацию!']);
                };
              };
            };
            $idp = (new \yii\db\Query())
            ->select('`id`')
            ->from('`tid`')
            ->orderby('`id` DESC')
            ->limit('1')
            ->all();
            $id = $idp[0]['id'] + 1;

            Yii::$app->db->createCommand()
            ->insert('tid', [
              'id' => $id
            ])->execute();

            return $this->render('tourneys-panel-'.$_GET['part'], [
              'model' => $model,
              'id' => $id
            ]);
          }elseif($_GET['part'] == 'edit') //EDIT
          {
            $message = '';
            if(isset($_GET['rights']) and  $_GET['rights'] == 'my'){
              $message = "Отображены все записи, к редактированию и предпросмотру которых вы имеете доступ.";
              $query = Tourneys::find()->where("`organisator` = '".Yii::$app->user->identity->username . "' 
                or `rights` LIKE '%". Yii::$app->user->identity->username . "%' or `rights` = '".Yii::$app->user->identity->username . "'");
            }else{
             $query = Tourneys::find();
           };
           $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
          ]);

           $getTourneys = $query->orderBy('date_actual_added DESC')
           ->offset($pagination->offset)
           ->limit($pagination->limit)
           ->all();

           return $this->render('tourneys-panel-'.$_GET['part'], [
            'pagination' => $pagination,
            'tourneys' => $getTourneys,
            'message' => $message
          ]);
         }elseif($_GET['part'] == 'organisations' )
         {
          $organisators = (new \yii\db\Query())
          ->select('`organisator`')
          ->from('`tourneys`')
          ->groupby('organisator')
          ->all();

          $i = 0;

          foreach($organisators as $organisator){
            $tourneyssnum[$i] = (new \yii\db\Query())
            ->select('COUNT(0)')
            ->from('`tourneys`')
            ->where("`organisator` = '".$organisator['organisator']."'")
            ->all();
            $i++;
          };

          return $this->render('tourneys-panel-'.$_GET['part'], ['organisators' => $organisators, 
            'tourneyssnum' => $tourneyssnum]);
        };
      }else
      {
       return $this->render('tourneys-panel');
     };
   }else
   {
    return $this->render('error', ['message' => 'Not Found (#404)', 'name' => 'Page not found.']);
  };
}
public function actionTourneyPreview($id){
  $query = Tourneys::find();
  if(isset($id)){
    $tourney = $query->where('`id` = '.$id)
    ->all();
    if(isset($tourney[0]->title) and isset(Yii::$app->user->identity->accessToken)){
     if((Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "tourneysmoderator") 
      or strripos($tourney[0]->rights, Yii::$app->user->identity->username) == true or $tourney[0]->rights == Yii::$app->user->identity->username 
      or $tourney[0]->author == Yii::$app->user->identity->username){
      $imgUrl = $tourney[0]['image'];
      return $this->render('tourneys-panel-test', [
        'model' => $tourney[0],
        'imgUrl' => $imgUrl
      ]);
    }else{
      $errname = 'Нет прав для просмотра страницы.'; 
      $errmessage = 'Вы не обладаете необходимыми правами для просмотра этой страницы.';
    };
  }else{
    $errname = 'Страница не найдена.'; 
    $errmessage = 'Заправшиваемая страница не найдена.';
  };
}else{
  $errname = 'Страница не найдена.'; 
  $errmessage = 'ID указан неверно.';
};
return $this->render('error', ['name' => $errname, 'message' => $errmessage]);
}
public function actionChangeStatusT($id, $action){
  if(!(Yii::$app->user->isGuest) and (Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "tourneysmoderator")){
    if(isset($id) and isset($action)){
      if($action == 'approve'){
        $status = 1;
        $message = "Событие одобрено.";
      }elseif($action == 'disapprove'){
        $status = -1;
        $message = "Событие отклонено.";
      };
      Yii::$app->db->createCommand()
      ->update('tourneys', 
        [
          'status' => $status
        ], '`id` ='.$id)
      ->execute();

      $query = Tourneys::find();
      $pagination = new Pagination([
        'defaultPageSize' => 10,
        'totalCount' => $query->count(),
      ]);

      $getTourneys = $query->orderBy('date_actual_added DESC')
      ->offset($pagination->offset)
      ->limit($pagination->limit)
      ->all();

      return $this->render('tourneys-panel-edit', [
        'message' => $message,
        'pagination' => $pagination,
        'tourneys' => $getTourneys]);
    }else{
      return $this->render('error', ['message' => 'ID или Action указаны неверно.', 'name' => 'Страница не найдена.']);
    };
  }else{
    return $this->render('error', ['message' => 'Вы не обладаете необходимыми правами для этого действия.', 'name' => 'Недостаточно прав.']);
  };
}
public function actionTourneyEditor($id){
 $model = new TourneysAddForm();
 if ($model->load(Yii::$app->request->post()))
   {
     if ($model->validate())
     {
      if($model->method == 'save')
      {
        $isthere = (new \yii\db\Query())
        ->select('*')
        ->from('`tourneys`')
        ->where("`id` =".$model->id)
        ->all();
        $model->file = UploadedFile::getInstance($model, 'file');
        $model->upload();

        $dis = $model->discipline;
        $res = $model->discipline;
        $model->discipline = '';
        $a = 0;
        while(isset($dis[$a])){
          if($a>0){
            $dis[$a] = " | ".$dis[$a];
          };
          $model->discipline  = $model['discipline'].$dis[$a];
          $a++;
        };

        $date_actual_added = date('Y-m-d');
        $ready = -1;
        $organisator = $model->organisator;
        $text = $model->text;
        $title = $model->title;
        $town = $model->town;
        $desc_visitors = $model->desc_visitors;
        $desc_players = $model->desc_players;
        $location = $model->location;
        $date_event = $model->date_event; 
        $date_last_day_event = ($model->date_last_day_event == '')?$date_event:$model->date_last_day_event;
        $ticket_cost = $model->ticket_cost; 
        $compete_cost = ($model->compete_cost == '')?NULL:$model->compete_cost;
        $buy_ticket_link = ($model->buy_ticket_link == '')?NULL:$model->buy_ticket_link;
        $reg_team_link = ($model->reg_team_link == '')?NULL:$model->reg_team_link;
        $contact = ($model->contact == '')?NULL:$model->contact;
        $discipline = $model->discipline; 
        $team_status = $model->team_status;
        $organisator = $model->organisator;
        $translation = ($model->translation == '')?NULL:$model->translation;

        if($isthere == [])
        {
          $image = ($model->file != '')?'uploaded-files/tourneys/'.'main-id' . $model->id . '.' . $model->file->extension:NULL;

          Yii::$app->db->createCommand()
          ->insert('tourneys', [
            'id' => $model->id, 
            'title' => $title, 
            'town' => $town, 
            'text' => $text, 
            'desc_visitors' => $desc_visitors, 
            'desc_players' => $desc_players, 
            'location' => $location, 
            'date_event' => $date_event, 
            'date_last_day_event' => $date_last_day_event, 
            'date_actual_added' => $date_actual_added, 
            'ticket_cost' => $ticket_cost, 
            'compete_cost' => $compete_cost, 
            'buy_ticket_link' => $buy_ticket_link, 
            'reg_team_link' => $reg_team_link, 
            'organisator' => $organisator, 
            'contact' => $contact, 
            'discipline' => $discipline, 
            'team_status' => $team_status, 
            'image' => $image, 
            'translation' => $translation, 
            'status' => -1,
            'rights' => Yii::$app->user->identity->username
          ])
          ->execute();

                //CREATED AND SAVED
          $model->discipline = $dis;
        }else{
          $model->image = ($model->file == '')?(str_replace('%20', ' ', $isthere[0]['image'])):('uploaded-files/tourneys/' . 'main-id' . $model->id . '.' . $model->file->extension);
          $image = ($model->file != '')?'uploaded-files/tourneys/'. 'main-id' . $model->id . '.' . $model->file->extension:$isthere[0]['image'];

          $tourney_to_check = (new \yii\db\Query())
          ->select('*')
          ->from('`tourneys`')
          ->where("`id` =".$model->id)
          ->all();

          if($this->checkRightsTourney($tourney_to_check) == true){
            Yii::$app->db->createCommand()
            ->update('tourneys', [
              'title' => $title, 
              'town' => $town, 
              'text' => $text, 
              'desc_visitors' => $desc_visitors, 
              'desc_players' => $desc_players, 
              'location' => $location, 
              'date_event' => $date_event, 
              'date_last_day_event' => $date_last_day_event, 
              'ticket_cost' => $ticket_cost, 
              'compete_cost' => $compete_cost, 
              'buy_ticket_link' => $buy_ticket_link, 
              'reg_team_link' => $reg_team_link, 
              'organisator' => $organisator, 
              'contact' => $contact, 
              'discipline' => $discipline, 
              'team_status' => $team_status, 
              'image' => $image, 
              'translation' => $translation, 
            ], '`id` ='.$model->id)
            ->execute();
                 //REPLACED AND SAVED
            $model->discipline = $dis;
          }else{
            return $this->render('tourneys-panel-add', ['model' => $model, 
              'message' => 'При обновлении данных произошла ошибка.', 'alerttype' => 'danger']);
          };
        }; 
        return $this->render('tourneys-panel-add', ['model' => $model, 
          'message' => 'Запись успешно сохранена! В будущем вы сможете вернуться к ее 
          редактированию в разделе "Редактировать события".']);
      }elseif($model->method == 'test')
      {
        $model->file = UploadedFile::getInstance($model, 'file');
        $model->upload();
        if($model->file != ''){
          $imgUrl = 'uploaded-files/tourneys/'. 'main-id' . $model->id . '.' . $model->file->extension;
        }elseif($model->image != ''){
         $imgUrl = str_replace(' ', '%20', $model->image);
       }else{
        $imgUrl = '';
      };

      $dis = $model->discipline;
      $model->discipline = '';
      $a = 0;
      while(isset($dis[$a])){
        if($a>0){
          $dis[$a] = " | ".$dis[$a];
        };
        $model->discipline  = $model['discipline'].$dis[$a];
        $a++;
      };

      return $this->render('tourneys-panel-test', ['model' => $model, 'imgUrl' => $imgUrl]); 
    }elseif($model->method == 'publish')
    {
      $isthere = (new \yii\db\Query())
      ->select('*')
      ->from('`tourneys`')
      ->where("`id` =".$model->id)
      ->all();

      $model->file = UploadedFile::getInstance($model, 'file');
      $model->upload();

      $model->image = ($model->file == '')?(str_replace('%20', ' ', $isthere[0]['image'])):('uploaded-files/tourneys/' . 'main-id' . $model->id . '.' . $model->file->extension);

      $dis = $model->discipline;
      $res = $model->discipline;
      $model->discipline = '';
      $a = 0;
      while(isset($dis[$a])){
        if($a>0){
          $dis[$a] = " | ".$dis[$a];
        };
        $model->discipline  = $model['discipline'].$dis[$a];
        $a++;
      };

      $date_actual_added = date('Y-m-d');
      $ready = 0;
      $organisator = $model->organisator;
      $text = $model->text;
      $title = $model->title;
      $image = ($model->image == '')?NULL:$model->image;
      $town = $model->town;
      $desc_visitors = $model->desc_visitors;
      $desc_players = $model->desc_players;
      $location = $model->location;
      $date_event = $model->date_event; 
      $date_last_day_event = ($model->date_last_day_event == '')?$date_event:$model->date_last_day_event;
      $ticket_cost = $model->ticket_cost; 
      $compete_cost = ($model->compete_cost == '')?NULL:$model->compete_cost;
      $buy_ticket_link = ($model->buy_ticket_link == '')?NULL:$model->buy_ticket_link;
      $reg_team_link = ($model->reg_team_link == '')?NULL:$model->reg_team_link;
      $contact = ($model->contact == '')?NULL:$model->contact;
      $discipline = $model->discipline; 
      $team_status = $model->team_status;
      $organisator = $model->organisator;
      $translation = ($model->translation == '')?NULL:$model->translation;

      if($isthere == [])
      {
        $image = ($model->file != '')?'uploaded-files/tourneys/'.'main-id' . $model->id . '.' . $model->file->extension:NULL;

        Yii::$app->db->createCommand()
        ->insert('tourneys', [
          'id' => $model->id, 
          'title' => $title, 
          'town' => $town, 
          'text' => $text, 
          'desc_visitors' => $desc_visitors, 
          'desc_players' => $desc_players, 
          'location' => $location, 
          'date_event' => $date_event, 
          'date_last_day_event' => $date_last_day_event, 
          'date_actual_added' => $date_actual_added, 
          'ticket_cost' => $ticket_cost, 
          'compete_cost' => $compete_cost, 
          'buy_ticket_link' => $buy_ticket_link, 
          'reg_team_link' => $reg_team_link, 
          'organisator' => $organisator, 
          'contact' => $contact, 
          'discipline' => $discipline, 
          'team_status' => $team_status, 
          'image' => $image, 
          'translation' => $translation, 
          'status' => $ready,
          'rights' => Yii::$app->user->identity->username
        ])
        ->execute();
                //CREATED AND SAVED
        $model->discipline = $dis;
      }else{
        $tourney_to_check = (new \yii\db\Query())
        ->select('*')
        ->from('`tourneys`')
        ->where("`id` =".$model->id)
        ->all();

        if($this->checkRightsTourney($tourney_to_check) == true){
          $image = ($model->file != '')?'uploaded-files/tourneys/'.'main-id' . $model->id . '.' . $model->file->extension:$isthere[0]['image'];

          Yii::$app->db->createCommand()
          ->update('tourneys', [
            'title' => $title, 
            'town' => $town, 
            'text' => $text, 
            'desc_visitors' => $desc_visitors, 
            'desc_players' => $desc_players, 
            'location' => $location, 
            'date_event' => $date_event, 
            'date_last_day_event' => $date_last_day_event, 
            'ticket_cost' => $ticket_cost, 
            'compete_cost' => $compete_cost, 
            'buy_ticket_link' => $buy_ticket_link, 
            'reg_team_link' => $reg_team_link, 
            'organisator' => $organisator, 
            'contact' => $contact, 
            'discipline' => $discipline, 
            'team_status' => $team_status, 
            'image' => $image, 
            'translation' => $translation, 
            'status' => ($isthere[0]['status'] == -1)?(0):($isthere[0]['status'])
          ], '`id` ='.$model->id)
          ->execute();
                 //REPLACED AND SAVED
          $model->discipline = $dis;
        }else{
          return $this->render('tourneys-panel-add', ['model' => $model, 
            'message' => 'При обновлении данных произошла ошибка.', 'alerttype' => 'danger']);
        };
      };
      return $this->render('tourneys-panel-add', 
        ['model' => $model, 'message' => ($isthere[0]['status'][0] == -1)?('Запись успешно отправлена на модерацию!'):('Запись успешно обновлена!')]);
    };
  };
};
$query = Tourneys::find();
if(isset($id)){
  $tourney = $query->where('`id` = '.$id)
  ->all();
  if(isset($tourney[0]->title)){
    if((Yii::$app->user->identity->accessToken == "admin" or Yii::$app->user->identity->accessToken == "tourneysmoderator") 
      or strripos($tourney[0]->rights, Yii::$app->user->identity->username) == true or $tourney[0]->rights == Yii::$app->user->identity->username 
      or $tourney[0]->organisator == Yii::$app->user->identity->username){
      $model = new TourneysAddForm();
      $model['text'] = $tourney[0]['text'];

      $model['id'] = $tourney[0]['id'];
      $model['title'] = $tourney[0]['title'];
      $model['town'] = $tourney[0]['town'];
      $model['text'] = $tourney[0]['text'];
      $model['desc_visitors'] = $tourney[0]['desc_visitors'];
      $model['desc_players'] = $tourney[0]['desc_players'];
      $model['location'] = $tourney[0]['location'];
      $model['date_event'] = $tourney[0]['date_event'];
      $model['date_last_day_event'] = $tourney[0]['date_last_day_event'];
      $model['ticket_cost'] = $tourney[0]['ticket_cost'];
      $model['compete_cost'] = $tourney[0]['compete_cost'];
      $model['buy_ticket_link'] = $tourney[0]['buy_ticket_link'];
      $model['reg_team_link'] = $tourney[0]['reg_team_link'];
      $model['contact'] = $tourney[0]['contact'];
      $model['discipline'] = explode(" | ", $tourney[0]['discipline']);
      $model['team_status'] = $tourney[0]['team_status'];
      $model['image'] = $tourney[0]['image'];
      $model['organisator'] = $tourney[0]['organisator'];
      $model['translation'] = $tourney[0]['translation'];

      return $this->render('tourneys-edit', [
        'model' => $model
      ]);
    }else{
      $errname = 'Нет прав для просмотра страницы.'; 
      $errmessage = 'Вы не обладаете необходимыми правами для просмотра этой страницы.';
    }
  }else{
    $errname = 'Страница не найдена.'; 
    $errmessage = 'Заправшиваемая страница не найдена.';
  }
}else{
  $errname = 'Страница не найдена.'; 
  $errmessage = 'ID указан неверно.';
};
return $this->render('error', ['name' => $errname, 'message' => $errmessage]);
}
}
