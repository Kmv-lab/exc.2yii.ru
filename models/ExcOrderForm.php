<?php


namespace app\models;


use yii\base\Model;

class ExcOrderForm extends Model
{

    public $date;
    public $price;
    public $price_ch;
    public $price_pref;

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
        ];
    }
}