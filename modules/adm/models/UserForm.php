<?php

namespace app\modules\adm\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class UserForm extends Model
{

    public $id;
    public $username;
    public $email;
    public $password;

    public function scenarios()
    {
        return [
            'create' => ['username', 'email', 'password'],
            'update' => ['username', 'email', 'password'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['id', 'required', 'on'=>'update'],
            ['username', 'unique', 'targetClass' => '\app\modules\adm\models\User', 'on'=>'create', 'message' => 'This username has already been taken.'],
            ['username', 'unique', 'targetClass' => '\app\modules\adm\models\User', 'on'=>'update', 'filter' => ['<>', 'id', $this->id], 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\modules\adm\models\User', 'on'=>'create', 'message' => 'This email address has already been taken.'],
            ['email', 'unique', 'targetClass' => '\app\modules\adm\models\User', 'on'=>'update', 'filter' => ['<>', 'id', $this->id], 'message' => 'This email address has already been taken.'],
            ['password', 'required', 'on'=>'create'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * create user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function create()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }

    public function update(){
        $col = $this->attributes;
        $user = User::findOne($col['id']);
        if(!empty($col['password']))
            $user->setPassword($this->password);
        $user->username = $col['username'];
        $user->email = $col['email'];
        $user->generateAuthKey();
        $user->save();
        return true;
    }
}