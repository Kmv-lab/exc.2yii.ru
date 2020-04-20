<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class ExcursionCategory extends ActiveRecord
{

    public static function tableName()
    {
        return 'exc_category';
    }

}