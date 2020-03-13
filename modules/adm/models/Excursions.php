<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class Excursions extends ActiveRecord
{

    public static function tableName()
    {
        return 'excursions';
    }

}