<?php


namespace app\modules\adm\models;


use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Drivers extends ActiveRecord
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
        return 'drivers';
    }

    public function rules(){
        return [
            [['name', 'expirians'], 'required'],
            ['file_name', 'file', 'extensions' => 'jpg, jpeg, png',
                'maxFiles' => 1,
                'minSize'=>Yii::$app->params['min_image_size_for_upload'],
                'maxSize'=>Yii::$app->params['max_image_size_for_upload'],
                'tooBig'=>'Одна или несколько фотографий больше {formattedLimit}',
                'tooSmall'=>'Одна или несколько фотографий меньше {formattedLimit}']
        ];
    }

    public function upload($nameInTable){
        $file = UploadedFile::getInstance($this, $nameInTable);

        if ($file){
            if (isset($this->oldAttributes[$nameInTable]) && $this->oldAttributes[$nameInTable]){
                $this->deleteOldPhoto($nameInTable, $this->oldAttributes[$nameInTable]);
            }
            $file->name = strtolower(md5(uniqid($file->baseName))). '.' . $file->extension;
            $file->saveAs( $this->DIR().'original/'.$file->name);

            return $file->name;
        }
        return false;
    }

    public function deleteOldPhoto($nameInTable, $fileName=null){
        if(!$fileName){
            $fileName = $this->{$nameInTable};
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