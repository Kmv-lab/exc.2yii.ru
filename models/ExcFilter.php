<?php


namespace app\models;


use yii\base\Model;

class ExcFilter extends Model
{

    public $date;
    public $type;
    public $duration;
    public $isActive;

    public function rules()
    {
        return [
        [['date', 'type', 'duration', 'isActive'], 'safe']
    ];
    }

}