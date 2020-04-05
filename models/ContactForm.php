<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\commands\helpers;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $phone;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'phone'],'filter','filter'=>'trim'],
            ['phone', 'required', 'message'=>'Введите ваш номер'],
            ['phone', 'match', 'pattern' => '/^\+7\(([0-9]{3})\)([0-9]{3})\-([0-9]{2})\-([0-9]{2})$/', 'message' => 'Не правильный формат' ],
            ['name', 'required', 'message'=>'Представьтесь'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels(){
        return [
            'name' => 'Ваше имя:',
            'phone'=> 'Ваш номер телефона:',
            ];
    }

    public function send()
    {
        if ($this->validate()) {
            $columns = $this->attributes;
            $columns['cities'] = static::getCities();
            $message    =   Yii::$app->view->render('@app/mail/contact',$columns,true);
            helpers::sendEmail($columns['form_name'], $message);
            return true;

        }
        return false;
    }
    public static function getCities(){
        return ['Пятигорск', 'Кисловодск'];
    }
}
