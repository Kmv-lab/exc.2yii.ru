<?php


namespace app\models;


use yii\base\Model;

class ExcFilter extends Model
{

    public $date;
    public $type;
    public $duration;

    public function rules()
    {
        return [
        [['date', 'type', 'duration'], 'safe']
    ];
    }

}