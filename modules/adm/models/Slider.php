<?php

namespace app\modules\adm\models;

use Yii;

/**
 * This is the model class for table "sliders".
 *
 * @property int $id_slider
 * @property string $name
 * @property int $is_active
 * @property string $files_name
 */
class Slider extends \yii\db\ActiveRecord
{
    public $files_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sliders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active'], 'integer'],
            [['name'], 'string', 'max' => 100],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_slider' => 'ID',
            'name' => 'Название',
            'is_active' => 'Включен',
            'files_name'=>'Фотографии'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSliderPhotos()
    {
        return $this->hasMany(SliderPhotos::className(), ['id_slider' => 'id_slider']);
    }


    public function upload(){
        if ($this->validate()) {
            foreach ($this->files_name as $file) {
                $model = new SliderPhotos();
                $model->attributes = ['id_slider'=>$this->id_slider, 'file_name'=>$file];
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
