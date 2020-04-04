<?php


namespace app\modules\adm\models;


use Yii;
use yii\db\ActiveRecord;

class ExcursionAdvices extends ActiveRecord
{

    public $umbrella;

    public $boots;

    public static function tableName()
    {
        return 'exc_advices';
    }

    public function getAdvices($idAdv=null){
        $arrayAdv = [
            'umbrella' => [
                'id' => 1,
                'name' => 'Ручной зонтик',
                'file' => 'beach-umbrella.png'
            ],
            'boots' => [
                'id' => 2,
                'name' => 'Водонепроницаемые боты',
                'file' => 'boots.png'
            ]
        ];

        if (!$idAdv){
            return $arrayAdv;
        }

        return $arrayAdv[$idAdv];
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