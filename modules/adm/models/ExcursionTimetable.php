<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class ExcursionTimetable extends ActiveRecord
{

    public static function tableName()
    {
        return 'timetable';
    }

    public function rules()
    {
        return [
            [['name', 'time', 'content', 'icon'], 'required'],
            ['icon', 'number'],
        ];
    }

    public static function DIR()
    {
        return '/content/icons/';
    }

    public static function DIRview()
    {
        return $_SERVER['DOCUMENT_ROOT'].'/content/icons/';
    }

    public function getIcons($id=null){
        $arrayIcons = [
            1 => [
                'file' => 'road-bus.png',
                'name' => 'Автобус'
            ],
            2 => [
                'file' => 'road-clock.png',
                'name' => 'Часы'
            ],
            3 => [
                'file' => 'road-track.png',
                'name' => 'Карты'
            ]
        ];

        if (!$id){
            return $arrayIcons;
        }

        return $arrayIcons[$id]['file'];
    }

}