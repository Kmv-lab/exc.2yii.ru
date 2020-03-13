<?php


namespace app\models;


use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class SansPrev extends ActiveRecord
{

    public static function DIR()
    {
        return Yii::$app->params['full_path_to_sanatoriums_photo'];
    }


    public static function DIRview()
    {
        return Yii::$app->params['path_to_sanatoriums_photo'];
    }

    public function upload(){
        $file = UploadedFile::getInstance($this, 'file_name');

        if ($file){
            if ($this->file_name){
                $this->deleteOldPhoto($this->file_name);
            }
            $file->name = strtolower(md5(uniqid($file->baseName))). '.' . $file->extension;
            $file->saveAs( $this->DIR().'original/'.$file->name);

            return $file->name;
        }

        return false;

    }

    public function deleteOldPhoto($fileName=null){
        if(!$fileName){
            $fileName = $this->file_name;
        }

        if (is_file($this->DIR().'original/'.$fileName)) {
            //vd($this->DIR().$fileName);
            unlink($this->DIR().'original/'.$fileName);
        }
        if (is_file($this->DIR().Yii::$app->params['resolution_main_sanatorium_photo'].'/'.$fileName)) {
            //vd($this->DIR().$fileName);
            unlink($this->DIR().Yii::$app->params['resolution_main_sanatorium_photo'].'/'.$fileName);
        }
        return;
    }

    public static function getDb()
    {
        return \Yii::$app->db;
    }

    public static function tableName()
    {
        return 'sanatoriums';
    }

}