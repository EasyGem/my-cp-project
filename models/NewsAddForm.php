<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $image
 * @property string $date
 * @property string $author
 * @property integer $ready
 */
class NewsAddForm extends \yii\db\ActiveRecord
{
  public $text;
  public $title;
  public $image;
  public $method;
  public $id;
  public $file;
  public $type;
  public $game;
  public $new_desc;
  
      public function rules()
    {
        return [
            [['text', 'title', 'method', 'id', 'type', 'new_desc'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['text', 'title', 'type', 'game', 'new_desc'], 'string'],
            [['id'], 'integer'],
            [['file'], 'file', 'extensions' => 'png, jpg', 
                   'skipOnEmpty' => true],
        ];
    }
        public function upload()
    {
        if ($this->validate() and $this->file != []) {
            $this->file->saveAs('uploaded-files/news/' . 'main-id' . $this->id . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }
}
