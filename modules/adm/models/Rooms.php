<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class Rooms extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->db;
    }

    public static function tableName()
    {
        return 'rooms';
    }
}