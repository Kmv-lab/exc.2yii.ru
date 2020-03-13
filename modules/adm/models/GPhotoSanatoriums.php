<?php


namespace app\modules\adm\models;


use app\commands\ImagickHelper;
use Yii;
use yii\db\ActiveRecord;

class GPhotoSanatoriums extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'g_photos_sanatoriums';
    }

    public static function DIR()
    {
        return Yii::$app->params['full_path_to_sanatoriums_galleries_images'];
    }


    public static function DIRview()
    {
        return Yii::$app->params['path_to_sanatoriums_galleries_images'];
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
            [['file_name'], 'string', 'max' => 50, 'min'=>2],
            [['id_gallery'], 'exist', 'skipOnError' => true, 'targetClass' => GaleriesSanatoriums::className(), 'targetAttribute' => ['id_gallery' => 'id']],
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
            'priority' => 'Приоритет',
            'file_name' => 'File Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(GaleriesSanatoriums::className(), ['id' => 'id_gallery']);
    }

    public function create(){

        $this->attributes = ImagickHelper::save($this, 1);
        if($this->save())
            return true;
        else
            return false;
    }
}