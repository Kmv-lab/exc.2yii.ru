<?php


namespace app\models;


use yii\db\ActiveRecord;

class CallRequest extends ActiveRecord
{

    public static function tableName()
    {
        return 'call_request';
    }

}