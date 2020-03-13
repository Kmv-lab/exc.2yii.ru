<?php


namespace app\modules\adm\models;


use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Excursions extends ActiveRecord
{

    public static function DIR()
    {
        return Yii::$app->params['full_path_to_excursion_photo'];
    }


    public static function DIRview()
    {
        return Yii::$app->params['path_to_excursion_photo'];
    }

    public static function tableName()
    {
        return 'excursions';
    }

    public function upload($name){
        $file = UploadedFile::getInstance($this, $name);

        //vd($this->{$name});

        if ($file){
            if ($this->{$name}){
                $this->deleteOldPhoto($name, $this->{$name});
            }
            $file->name = strtolower(md5(uniqid($file->baseName))). '.' . $file->extension;
            $file->saveAs( $this->DIR().'original/'.$file->name);

            return $file->name;
        }
        return false;
    }

    public function deleteOldPhoto($name, $fileName=null){
        if(!$fileName){
            $fileName = $this->{$name};
        }

        if (is_file($this->DIR().'original/'.$fileName)) {
            //vd($this->DIR().$fileName);
            unlink($this->DIR().'original/'.$fileName);
        }
        if (is_file($this->DIR().Yii::$app->params['resolution_main_excursion_photo'].'/'.$fileName)) {
            //vd($this->DIR().$fileName);
            unlink($this->DIR().Yii::$app->params['resolution_main_excursion_photo'].'/'.$fileName);
        }
        return;
    }

}