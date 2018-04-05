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
class News extends \yii\db\ActiveRecord
{

}
