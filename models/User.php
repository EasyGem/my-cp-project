<?php

namespace app\models;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    public static function getUsers(){
      $sql = (new \yii\db\Query())
            ->select('`id`, `username`, `password`, `authKey`, `accessToken`')
            ->from('`users`')
            ->all();
            
      $circle = 0;      
      while(isset($sql[$circle])){
        $users[$sql[$circle]['id']] = $sql[$circle];
        $circle++;
      };
      return $users;
    }
    
    //$users = $this->getUsers();
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $users = self::getUsers();
        return isset($users[$id]) ? new static($users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $users = self::getUsers();
        foreach ($users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $users = self::getUsers();
        foreach ($users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
