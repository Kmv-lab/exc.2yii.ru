<?php

namespace app\modules\adm\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "news".
 *
 * @property int $id_news
 * @property string $name
 * @property string $alias
 * @property string $content
 * @property string $anons
 * @property string $seo_h1
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property int $is_active
 * @property string $file_name
 * @property int $date_publication
 * @property int $date_create
 * @property int $date_update
 * @property int $type
 *
 */
class News extends \yii\db\ActiveRecord
{

    public static function DIR()
    {
        return Yii::$app->params['full_path_to_news_images'];
    }


    public static function DIRview()
    {
        return Yii::$app->params['path_to_news_images'];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
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
            [['alias','name','seo_h1','seo_title','seo_description','seo_keywords', 'content', 'anons'],'filter','filter'=>'trim'],
            ['alias','filter','filter'=>'strtolower'],
            [['name', 'alias', 'content', 'anons', 'date_publication', 'date_create', 'date_update'], 'required'],
            [['date_publication'], 'date', 'format'=>'php:d.m.Y', 'timestampAttribute'=>'date_publication'],
            [['content'], 'string'],
            [['is_active'], 'boolean'],
            [['type'], 'integer', 'max'=>3, 'min'=>1],
            [['name', 'alias'], 'string', 'max' => 100],
            [['seo_h1', 'seo_title'], 'string', 'max' => 200],
            [['anons', 'seo_description', 'seo_keywords'], 'string', 'max' => 400],
            [['alias'], 'unique', 'targetAttribute' => ['alias']],
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
            'id_news' => 'ID',
            'name' => 'Название',
            'alias' => 'Алиас',
            'content' => 'Контент',
            'anons' => 'Анонс',
            'seo_h1' => 'Seo H1',
            'seo_title' => 'Seo Title',
            'seo_description' => 'Seo Description',
            'seo_keywords' => 'Seo Keywords',
            'is_active' => 'Включена',
            'file_name' => 'Фото',
            'date_publication' => 'Дата',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }

    public function upload($type = 0){
        if($type > 0)
            $this->type = $type;
        if($this->validate()){
            if($this->file_name = UploadedFile::getInstance($this, 'file_name')){//why?
                if($this->validate()){
                    //vd($this->attributes);
                    if($this->isNewRecord){//Пологаю - проверка первыя ли запись этого элемента
                        $this->file_name = '';//WHY? PERCHÉ? ПОЧЕМУ?
                        $this->save(false);
                        $this->file_name = UploadedFile::getInstance($this, 'file_name');//WHY TWICE?
                    }else{
                        $this->delete_photo(true);
                    }
                    $this->file_name->saveAs( $this->DIR().'original/'.$this->id_news.'.'.$this->file_name->extension);//сохранение картинки в нужную папку(ogiginal)
                    $this->file_name = $this->id_news.'.'.$this->file_name->extension;
                    //vd($this->file_name, false);//Активируется при закгрузке картинки. Это оно.
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
