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
    public $office;
    public $form_name;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'form_name'],'filter','filter'=>'trim'],
            ['phone', 'required', 'message'=>'Введите ваш номер'],
            ['phone', 'match', 'pattern' => '/^\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Не правильный формат' ],
            ['name', 'required', 'message'=>'Представьтесь'],
            ['office','integer', 'max'=> 100, 'min'=>0],
            ['form_name','string', 'max'=> 100, 'min'=>1],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels(){
        return [
            'name' => 'Ваше имя:',
            'phone'=> 'Ваш номер телефона:',
            'office'=>'Выберите офис:', ];
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
