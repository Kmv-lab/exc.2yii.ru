<?php

namespace app\modules\adm\models;


use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "spec".
 *
 * @property int $id
 * @property string $name
 * @property string $anons
 * @property int $is_active
 * @property string $file_name
 * @property int $date_publication
 * @property int $date_create
 * @property int $date_update
 * @property int $date_start
 * @property int $date_end
 * @property int $min_price
 */
class Spec extends \yii\db\ActiveRecord
{

    public static function DIR()
    {
        return Yii::$app->params['full_path_to_spec_images'];
    }


    public static function DIRview()
    {
        return Yii::$app->params['path_to_spec_images'];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spec';
    }


    function init(){
        if($this->isNewRecord){
            $this->date_create = time();
            $this->date_publication = time();
            $this->is_active = 1;
        }
        $this->date_update = time();
        return parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'anons'],'filter','filter'=>'trim'],
            [['name', 'anons', 'date_publication', 'date_create', 'date_update'], 'required'],
            [['date_publication'], 'date', 'format'=>'php:d.m.Y', 'timestampAttribute'=>'date_publication'],
            [['date_start'], 'date', 'format'=>'php:d.m.Y', 'timestampAttribute'=>'date_start'],
            [['date_end'], 'date', 'format'=>'php:d.m.Y', 'timestampAttribute'=>'date_end'],
            [['is_active'], 'boolean'],
            [['name'], 'string', 'max' => 100],
            [['anons'], 'string', 'max' => 400],
            [['min_price'], 'integer', 'max' => 1000000000, 'min'=>0],
            ['file_name', 'file',
                'extensions' => 'png, jpeg, jpg',
                'maxFiles' => 1,
                'minSize'=>Yii::$app->params['min_image_size_for_upload'],
                'maxSize'=>Yii::$app->params['max_image_size_for_upload'],
                'tooBig'=>'Фотография больше {formattedLimit}',
                'tooSmall'=>'Фотография меньше {formattedLimit}',],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'anons' => 'Анонс',
            'is_active' => 'Включена',
            'file_name' => 'Фото',
            'date_publication' => 'Дата',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'date_start' => 'Начало акции',
            'date_end' => 'Конец акции',
            'min_price' => 'Цена от',
        ];
    }


    public function upload(){
        if($this->validate()){
            if($this->file_name = UploadedFile::getInstance($this, 'file_name')){
                if($this->validate()){
                    if($this->isNewRecord){
                        $this->file_name = '';
                        $this->save(false);
                        $this->file_name = UploadedFile::getInstance($this, 'file_name');
                    }else{
                        $this->delete_photo(true);
                    }
                    $this->file_name->saveAs( $this->DIR().'original/'.$this->id.'.'.$this->file_name->extension);
                    $this->file_name = $this->id.'.'.$this->file_name->extension;
                }else{
                    return false;
                }
            }else{
                $this->file_name = $this->getOldAttribute('file_name');
            }
            $this->save(false);
            return true;
        }
        return false;
    }
    public function delete_photo($old=false){
        if(!empty($this->file_name)){
            if($old){
                $file_name = $this->getOldAttribute('file_name');
            }else{
                $file_name = $this->file_name;
                $this->file_name = '';
            }
            $skip = array('.', '..');
            $files = scandir($this->DIR());
            foreach($files as $file) {
                if(!in_array($file, $skip) && file_exists($this->DIR().$file.'/'.$file_name)){
                    //echo($file . '<br />');
                    @unlink($this->DIR().$file.'/'.$file_name);
                }
            }
        }
        return true;
    }
}