<?php


namespace app\models;


use yii\base\Model;

class BookingForm extends Model
{
    public $date;
    public $price;
    public $price_ch;
    public $price_pref;
    public $personName;
    public $personPhone;
    public $personEmail;

    public function rules()
    {
        return [
            ['date', 'required', 'message'=>'Выберете дату отправления'],
            [['price','price_ch','price_pref'], function($attribute,$params){
                $result = $this->price + $this->price_ch + $this->price_pref;
                if($result)
                    return true;
                $this->addError($attribute, 'Укажите количество броней');
                return false;
            }],
            ['personName', 'required', 'message' => 'Представьтесь'],
            ['personPhone', 'required', 'message' => 'Введите номер'],
            ['personPhone', 'match', 'pattern' => '/^\+7\(([0-9]{3})\)([0-9]{3})\-([0-9]{2})\-([0-9]{2})$/', 'message' => 'Некорректный номер телефона' ],
            ['personEmail', 'required', 'message' => 'Введите eMail'],
            ['personEmail', 'email', 'message' => 'Неправильный формат электронной почты']
        ];
    }
}