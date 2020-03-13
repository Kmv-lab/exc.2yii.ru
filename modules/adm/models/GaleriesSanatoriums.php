<?php


namespace app\modules\adm\models;


use Yii;
use yii\db\ActiveRecord;

class GaleriesSanatoriums extends ActiveRecord
{
    public $files_name;

    public static function tableName()
    {
        return 'galleries_sanatoriums';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 200],
            ['files_name', 'file',
                'extensions' => 'png, jpg, jpeg',
                'maxFiles' => 10,
                'minSize'=>Yii::$app->params['min_image_size_for_upload'],
                'maxSize'=>Yii::$app->params['max_image_size_for_upload'],
                'tooBig'=>'Одна или несколько фотографий больше {formattedLimit}',
                'tooSmall'=>'Одна или несколько фотографий меньше {formattedLimit}',],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleriesPhotos()
    {
        return $this->hasMany(GPhotoSanatoriums::className(), ['id_gallery' => 'id']);
    }

    public function upload(){
        if ($this->validate()) {
            foreach ($this->files_name as $file) {
                $model = new GPhotoSanatoriums();
                $model->attributes = ['id_gallery'=>$this->id, 'file_name'=>$file];
                if(!$model->create()){
                    return false;
                }
                //$file->saveAs( Yii::$app->params['full_path_to_galleries_images'].'original/'.$file->baseName.'.'.$file->extension);
            }
            return true;
        }else{
            return false;
        }
    }
}