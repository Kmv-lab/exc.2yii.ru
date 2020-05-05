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

    public static function getTowns($idTown = null){
        $townArray = [
            1 => 'Ессентуки',
            2 => 'Пятигорск',
            3 => 'Железноводск'
        ];
        if ($idTown && isset($townArray[$idTown])){
            return $townArray[$idTown];
        }

        return $townArray;
    }

    public static function getCategories($idCategory = null){
        $categoryArray = [
            0 => 'Категория',
            1 => 'Подъём в горы',
            2 => 'Верховые экскурсии',
            3 => 'Плавание',
            4 => 'Позновательные экскурсии'
        ];

        if ($idCategory && isset($categoryArray[$idCategory])){
            return $categoryArray[$idCategory];
        }

        return $categoryArray;
    }

    public static function DIRview()
    {
        return Yii::$app->params['path_to_excursion_photo'];
    }

    public function rules()
    {
        return [
            [['desc','video_src','distance','rating', 'is_hit'],
                'filter','filter'=>'trim'],
            [['name','alias','id_guide','id_town','duration','time_start','time_end'], 'required'],
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