<?php

namespace app\models;
use yii\base\Model;

class LoginForm extends Model
{
    public $login;
    public $password;

    public function rules() {
        return [
            [['login', 'password'], 'trim'],
            [
                ['login', 'password'], 'required',
            ],
            [['password'], 'string', 'min' => 7],
        ];
    }

    public function attributeLabels() {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
        ];
    }
}

?>