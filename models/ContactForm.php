<?php

namespace app\models;

use yii\base\Model;

class ContactForm extends Model
{
    public $topic;
    public $email;
    public $text;

    public function rules()
    {
        return [
            [['email', 'topic', 'text'], 'required', 'message' => 'Поле обязательно для заполнения'],
            ['email', 'email', 'message' => 'Некорректный e-mail адрес'],
        ];
    }
}
