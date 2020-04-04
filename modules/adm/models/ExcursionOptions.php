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

    public function getOptions($idOpt = null){
        $options = [
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

        if (!isset($idOpt)){
            return $options;
        }
        return $options[$idOpt];

    }

    public function getOptionsName($id){
        $advices = $this->getOptions();
        return $advices[$id]['name'];
    }

    public function getOptionsFile($id){
        $advices = $this->getOptions();
        return $advices[$id]['file'];
    }

    public function getOptionsIds(){
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