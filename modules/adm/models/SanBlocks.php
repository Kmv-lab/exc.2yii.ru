<?php


namespace app\modules\adm\models;

use yii\db\ActiveRecord;

class SanBlocks extends ActiveRecord
{
    public static function tableName()
    {
        return 'san_blocks';
    }
}