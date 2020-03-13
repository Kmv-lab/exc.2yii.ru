<?php

namespace app\modules\adm\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "static_seo".
 *
 * @property int $id
 * @property string $name
 * @property string $seo_title
 * @property string $seo_h1
 * @property string $seo_h1_span
 * @property string $seo_description
 * @property string $seo_keywords
 */
class StaticSeo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'static_seo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seo_h1', 'seo_h1_span','seo_title','seo_description','seo_keywords', 'name'],'filter','filter'=>'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['seo_title', 'seo_h1', 'seo_h1_span'], 'string', 'max' => 200],
            [['seo_description', 'seo_keywords'], 'string', 'max' => 400],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Страница',
            'seo_title' => 'Seo Title',
            'seo_h1' => 'Seo H1',
            'seo_description' => 'Seo Description',
            'seo_keywords' => 'Seo Keywords',
            'seo_h1_span' => 'H1 подпись'
        ];
    }

    public function upload(){
        if($this->validate()){
            $this->save(false);
            return true;
        }
        return false;
    }
}
