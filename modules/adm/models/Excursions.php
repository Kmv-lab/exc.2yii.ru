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

    public function rules()
    {
        return [
            [['name','desc','video_src','id_guide','distance',
                'rating','id_town', 'is_hit', 'duration','time_start','time_end'],
                'filter','filter'=>'trim'],
            ['main_photo', 'file',
                'extensions' => 'jpg, jpeg, png',
                'maxFiles' => 1,
                'minSize'=>Yii::$app->params['min_image_size_for_upload'],
                'maxSize'=>Yii::$app->params['max_image_size_for_upload'],
                'tooBig'=>'Одна или несколько фотографий больше {formattedLimit}',
                'tooSmall'=>'Одна или несколько фотографий меньше {formattedLimit}',],
            ['map', 'file',
                'extensions' => 'jpg, jpeg, png',
                'maxFiles' => 1,
                'minSize'=>Yii::$app->params['min_image_size_for_upload'],
                'maxSize'=>Yii::$app->params['max_image_size_for_upload'],
                'tooBig'=>'Одна или несколько фотографий больше {formattedLimit}',
                'tooSmall'=>'Одна или несколько фотографий меньше {formattedLimit}',],
        ];
    }

    public static function tableName()
    {
        return 'excursions';
    }

    public function upload($name){
        $file = UploadedFile::getInstance($this, $name);

        if ($file){
            if (isset($this->oldAttributes[$name]) && $this->oldAttributes[$name]){
                $this->deleteOldPhoto($name, $this->oldAttributes[$name]);
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

    public function attrLoad($post, $nameParam = null){
        if (parent::load($post, $nameParam)){
            $data = $post[$this->formName()];

            foreach ($data as $key => $value){
                $this->$key = $value;
            }
        }
        return ;


    }

}