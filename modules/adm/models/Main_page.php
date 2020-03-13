<?php


namespace app\modules\adm\models;


use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Main_page extends ActiveRecord{

    public $curType;

    public static function DIR()
    {
        return Yii::$app->params['full_path_to_main_page_images'];
    }

    public static function DIRview()
    {
        return Yii::$app->params['path_to_main_page_images'];
    }

    public function rules()
    {
        if ($this->type == 3){
            return [
                [   'content', 'file',
                    'extensions' => 'png, jpeg, jpg',
                    'maxFiles' => 1,
                    'minSize'=>Yii::$app->params['min_image_size_for_upload'],
                    'maxSize'=>Yii::$app->params['max_image_size_for_upload'],
                    'tooBig'=>'Фотография больше {formattedLimit}',
                    'tooSmall'=>'Фотография меньше {formattedLimit}',],
            ];
        }
        return [
            [['content', 'type'], 'required']
        ];
    }

    public function deleteOldImage($fileName){
        if (file_exists(Yii::getAlias('@webroot').$fileName)) {
            unlink(Yii::getAlias('@webroot').$fileName);
        }
        return;
    }

    public function upload(){
        $file = UploadedFile::getInstance($this, 'content');

        $file->name = strtolower(md5(uniqid($file->baseName))). '.' . $file->extension;

        $file->saveAs( $this->DIR().$file->name);

        return $this->DIRview().$file->name;
    }

}