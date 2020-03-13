<?php

namespace app\modules\adm\models;

use Yii;
use app\commands\ImagickHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "galleries_photos".
 *
 * @property int $id_photo
 * @property int $id_gallery
 * @property string $alt
 * @property int $priority
 * @property string $file_name
 *
 * @property Gallery $gallery
 */
class GalleryPhoto extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'galleries_photos';
    }

    public static function DIR()
    {
        return Yii::$app->params['full_path_to_galleries_images'];
    }


    public static function DIRview()
    {
        return Yii::$app->params['path_to_galleries_images'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_gallery', 'file_name'], 'required'],
            [['priority'], 'integer', 'max'=>99],
            ['priority', 'default', 'value' => 99],
            [['id_gallery'], 'integer'],
            [['alt'], 'string', 'max' => 200],
            [['file_name'], 'string', 'max' => 50, 'min'=>2],
            [['id_gallery'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::className(), 'targetAttribute' => ['id_gallery' => 'id_gallery']],
            [['file_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_photo' => 'Id Photo',
            'id_gallery' => 'Id Gallery',
            'alt' => 'Alt',
            'priority' => 'Приоритет',
            'file_name' => 'File Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(Gallery::className(), ['id_gallery' => 'id_gallery']);
    }

    public function create(){

        $this->attributes = ImagickHelper::save($this, 1);
        if($this->save())
            return true;
        else
            return false;
    }
}
