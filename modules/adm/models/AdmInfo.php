<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class AdmInfo extends ActiveRecord
{
    public static function tableName()
    {
        return 'adm_info';
    }
}