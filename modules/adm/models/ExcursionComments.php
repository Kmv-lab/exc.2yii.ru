<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class ExcursionComments extends ActiveRecord
{

    public function getTypes(){
        return [
            'Текстовый отзыв',
            'Видео отзыв'
        ];
    }

    public static function tableName()
    {
        return 'exc_comments';
    }

    public function rules()
    {
        return [
            [['type', 'content', 'date'], 'required'],
            ['rating', 'number'],
            [['name', 'date'], 'safe']
        ];
    }

}