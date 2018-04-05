<?php

namespace app\models;

use yii;

class AddUserForm extends \yii\db\ActiveRecord
{
    public $username;
    public $password;
    public $rep_password;
    public $accessToken;
    public $contact_link;
    
    public function rules(){
      return [
      [['username', 'password', 'rep_password', 'accessToken'], 
      'required', 'message' => 'Поле обязательно для заполнения'],
      [['contact_link'], 'string']
      ];
    }
}