<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class ExcursionPrices extends ActiveRecord
{
    public static function tableName()
    {
        return 'prices';
    }

    public static function getDayArray($idDay = null){
        $days = [
            1 => 'Пн',
            2 => 'Вт',
            3 => 'Ср',
            4 => 'Чт',
            5 => 'Пт',
            6 => 'Сб',
            7 => 'Вс',
        ];

        if ($idDay && isset($days[$idDay])){
            return $days[$idDay];
        }

        return $days;
    }

    public static function getDaysArray(){
        return [
            1 => 'Понедельник',
            2 => 'Вторник',
            3 => 'Среда',
            4 => 'Четверг',
            5 => 'Пятиница',
            6 => 'Суббота',
            7 => 'Воскресение',
        ];
    }

    public function rules()
    {
        return [
            [['start', 'end', 'price',], 'required'],
            [['price', 'price_ch', 'price_pref'], 'number'],
            [['mon','tue','wed','thu','fri','sat','sun'], 'boolean'],
            [['mon','tue','wed','thu','fri','sat','sun'], function($attribute,$params){
                $result = $this->mon + $this->tue + $this->wed + $this->thu + $this->fri + $this->sat + $this->sun;
                if($result)
                    return true;
                $this->addError($attribute, 'Указать день недели');
                return false;
            }],
        ];
    }

}