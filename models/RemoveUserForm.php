<?php

namespace app\models;

use yii;

class RemoveUserForm extends \yii\db\ActiveRecord
{
    public $username;
    public $password;
    
    public function rules(){
      return [
      [['username', 'password'], 
      'required', 'message' => 'Поле обязательно для заполнения'],
      ];
    }
}