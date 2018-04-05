<?php

namespace app\models;

use Yii;

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
class TourneysAddForm extends \yii\db\ActiveRecord
{
  public $id;
  public $title;
  public $town;
  public $text;
  public $desc_visitors;
  public $desc_players;
  public $location;
  public $date_event; 
  public $date_last_day_event;
  public $ticket_cost; 
  public $compete_cost;
  public $buy_ticket_link;
  public $reg_team_link;
  public $contact;
  public $discipline; 
  public $team_status;
  public $image;
  public $organisator;
  public $translation;
  
  public $tickstatus;
  public $file;
  public $method;
  
      public function rules()
    {
        return [
            [['id', 'title', 'town', 'text', 'desc_visitors', 'desc_players', 'location', 'date_event', 'ticket_cost',
            'contact', 'organisator', 'discipline', 'team_status', 'method'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [[
            'date_last_day_event',
            'compete_cost',
            'buy_ticket_link',
            'reg_team_link',
            'tickstatus',
            'image',
            'translation',
            ], 'string', 'message' => 'Поле должно быть строкой'],
            [['file'], 'file', 'extensions' => 'png, jpg', 
                   'skipOnEmpty' => true],
            [['id'], 'integer'],
        ];
    }
        public function upload()
    {
        if ($this->validate() and $this->file != []) {
            $this->file->saveAs('uploaded-files/tourneys/' . 'main-id' . $this->id . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }
}
