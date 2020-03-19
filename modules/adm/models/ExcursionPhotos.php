<?php


namespace app\modules\adm\models;


use Yii;
use yii\db\ActiveRecord;

class ExcursionPhotos extends ActiveRecord
{

    public function rules()
    {
        return [
            [['name'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 18],
        ];
    }

    public static function tableName()
    {
        return 'exc_photos';
    }

    public static function DIR()
    {
        return $_SERVER['DOCUMENT_ROOT'].'/content/excursions/';
    }

    public static function DIRview()
    {
        return '/content/excursions/';
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->name as $file) {
                $fileName = strtolower(md5(uniqid($file->baseName))). '.' . $file->extension;
                $file->saveAs($this->DIR().'original/' . $fileName);
                $filesName[] = $fileName;
            }
            return $filesName;
        } else {
            return false;
        }
    }

    public function deletePhoto(){
        $fileName = $this->name;

        if (is_file($this->DIR().'original/'.$fileName)) {
            unlink($this->DIR().'original/'.$fileName);
        }

        if (is_file($this->DIR().Yii::$app->params['resolution_excursion_photo'].'/'.$fileName)) {
            unlink($this->DIR().Yii::$app->params['resolution_excursion_photo'].'/'.$fileName);
        }
    }

}