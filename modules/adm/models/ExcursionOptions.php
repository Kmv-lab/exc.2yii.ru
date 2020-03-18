<?php


namespace app\modules\adm\models;


use yii\db\ActiveRecord;

class ExcursionOptions extends ActiveRecord
{

    public $dinner;

    public $hostelRoom;

    public $museum;

    public static function tableName()
    {
        return 'exc_options';
    }

    public function getAdvices(){
        return [
            'dinner' => [
                'id' => 1,
                'name' => 'Обед в кафе',
                'file' => 'restaurant.png'
            ],
            'hostelRoom' => [
                'id' => 2,
                'name' => 'Номер в мотеле',
                'file' => 'bed.png'
            ],
            'museum' => [
                'id' => 3,
                'name' => 'Вход в музей',
                'file' => 'art-museum.png'
            ]
        ];
    }

    public function getAdvicesName($id){
        $advices = $this->getAdvices();
        return $advices[$id]['name'];
    }

    public function getAdvicesFile($id){
        $advices = $this->getAdvices();
        return $advices[$id]['file'];
    }

    public function getAdvicesIds(){
        return get_object_vars($this);
    }

    public static function DIR()
    {
        return '/content/icons/';
    }

    public static function DIRview()
    {
        return $_SERVER['DOCUMENT_ROOT'].'/content/icons/';
    }

}