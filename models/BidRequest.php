<?php


namespace app\models;


use yii\db\ActiveRecord;

class BidRequest extends ActiveRecord
{

    public static function tableName()
    {
        return 'bid_request';
    }

}