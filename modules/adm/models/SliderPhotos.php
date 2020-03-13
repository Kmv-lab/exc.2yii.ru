<?php

namespace app\modules\adm\models;

use Yii;
use app\commands\ImagickHelper;

/**
 * This is the model class for table "slider_photos".
 *
 * @property int $id_photo
 * @property int $id_slider
 * @property string $alt
 * @property string $file_name
 * @property int $priority
 * @property string $text_1
 * @property string $text_2
 * @property string $text_3
 * @property string $url
 */
class SliderPhotos extends \yii\db\ActiveRecord
{


    public static function DIR()
    {
        return Yii::$app->params['full_path_to_sliders_images'];
    }


    public static function DIRview()
    {
        return Yii::$app->params['path_to_sliders_images'];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'slider_photos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_slider', 'file_name'], 'required'],
            [['id_slider', 'priority'], 'integer'],
            ['priority', 'default', 'value' => 99],
            [['alt'], 'string', 'max' => 256],
            [['file_name'], 'string', 'max' => 50],
            [['text_1', 'text_2', 'text_3', 'url'], 'string', 'max' => 200],
            [['id_slider'], 'exist', 'skipOnError' => true, 'targetClass' => Slider::className(), 'targetAttribute' => ['id_slider' => 'id_slider']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_photo' => 'Id Photo',
            'id_slider' => 'Id Slider',
            'alt' => 'Alt',
            'file_name' => 'File Name',
            'priority' => 'Приоритет',
            'text_1' => 'Текст 1',
            'text_2' => 'Текст 2',
            'text_3' => 'Текст 3',
            'url' => 'URL кнопки "подробнее"',
        ];
    }


    public function create(){
        $this->attributes = ImagickHelper::save($this, 1);
        if($this->save())
            return true;
        else
            return false;
    }
}
