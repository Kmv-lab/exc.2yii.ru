<?php

namespace app\modules\adm\models;

use Yii;
use app\commands\PagesHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "pages".
 *
 * @property int $id_page
 * @property int $id_parent_page
 * @property string $page_alias
 * @property string $page_name
 * @property string $page_menu_name
 * @property string $page_breadcrumbs_name
 * @property string $page_link_title
 * @property int $page_priority
 * @property string $page_content
 * @property string $seo_h1
 * @property string $seo_h1_span
 * @property string $seo_description
 * @property string $seo_title
 * @property string $seo_keywords
 * @property int $is_active
 * @property int $show_in_menu
 * @property int $show_childs
 * @property int $date_create
 * @property int $date_update
 * @property int $no_del
 * @property int $no_change_position
 */
class Page extends \yii\db\ActiveRecord
{
    public static function DIR()
    {
        return Yii::$app->params['full_path_to_pages_images'];
    }


    public static function DIRview()
    {
        return Yii::$app->params['path_to_pages_images'];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pages';
    }

    function init(){
        if($this->isNewRecord){
            $this->page_priority = 99;
            $this->is_active = 1;
            $this->show_in_menu = 1;
            $this->show_childs = 1;
            $this->show_sitemap = 1;
        }
        return parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_alias','page_name','page_menu_name','page_breadcrumbs_name','page_link_title',
                'page_priority','seo_h1', 'seo_h1_span','seo_title','seo_description','seo_keywords', 'page_content'],
                'filter','filter'=>'trim'],
            ['page_alias','filter','filter'=>'strtolower'],

            [['id_parent_page','page_alias','page_name','page_menu_name'],'required'],
            [['seo_h1', 'seo_h1_span','seo_title'], 'string', 'max'=> 200],
            [['seo_description','seo_keywords'],'string', 'max'=> 400],
            [['is_active','show_in_menu','show_childs', 'no_del', 'show_sitemap'],'boolean'],
            ['page_priority', 'integer', 'max'=>99 ],
            [['seo_description','seo_keywords'],'string', 'max'=>400],
            ['page_content','string', 'max'=>100000],
            ['id_parent_page', 'integer'],
            [['page_alias','page_breadcrumbs_name'],'string', 'max'=> 100],
            [['page_name','page_menu_name'],'string', 'max'=> 150],
            ['page_link_title','string', 'max'=> 200],
            ['page_priority', 'default', 'value' => 99],
            [['id_parent_page', 'page_alias'], 'unique', 'targetAttribute' => ['id_parent_page', 'page_alias']],
            ['file_name', 'file',
                'extensions' => 'jpg, jpeg, png',
                'maxFiles' => 1,
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
            'seo_h1'                =>  'SEO H1',
            'seo_h1_span'           =>  'H1 подпись',
            'seo_title'             =>  'SEO TITLE',
            'seo_description'       =>  'SEO DESCRIPTION',
            'seo_keywords'          =>  'SEO KEYWORDS',
            'is_active'             =>  'Включен',
            'show_in_menu'          =>  'Показывать в меню',
            'show_childs'           =>  'Отображать подразделы',
            'show_sitemap'          =>  'Выводить в сайтмап',
            'page_alias'            =>  'Алиас',
            'id_parent_page'        =>  'Родительский раздел',
            'page_name'             =>  'Название',
            'page_menu_name'        =>  'Название в меню',
            'mob_menu_name'         =>  'Название в мобильном меню',
            'page_breadcrumbs_name' =>  'Название в хлебных',
            'page_link_title'       =>  'Тайтл для ссылки',
            'page_content'          =>  'Содержимое страницы',
            'page_priority'         =>  'Приоритет',
            'page_css'              =>  'Стили страницы',
            'page_js'               =>  'Скрипты страницы',
            'file_name'             =>  'Фото',
        ];
    }

    public function getAllChildPages($id=0, $return=[], $level=1)
    {
        if ($id == 0) $return['list']['0'] = 'Главная';

        $pages = PagesHelper::select(
            Yii::$app->params['pages'],
            array(),
            array(
                array('attr_name'   =>  'id_parent_page',   'operand'=> '=', 'value'=> $id ),
            ),
            array('attr_name'  => 'page_priority', 'sort_type'=>'ASC')
        );//Dump::d($pages);die;
        foreach ($pages as $page)
        {
            $line = '|';
            for ($i=1; $i<=$level; $i++)
            {
                $line .= '—';
            }
            $return['list'][$page['id_page']] = $line.' '.$page['page_name'];
            if($this->id_page == $page['id_page'])
                $return['disabled'][$page['id_page']] = ['disabled'=>true];
            $return = self::getAllChildPages($page['id_page'],$return,$level+1);
        }
        if(!isset($return['disabled']) || !is_array($return['disabled']))
            $return['disabled'] = [];
        return $return;
    }


    public function upload(){
        if($this->validate()){
            if($this->file_name = UploadedFile::getInstance($this, 'file_name')){
                if($this->validate()){
                    @unlink($this->DIR().$this->getOldAttribute('file_name'));
                    if($this->isNewRecord){
                        $this->file_name = '';
                        $this->save(false);
                        $this->file_name = UploadedFile::getInstance($this, 'file_name');
                    }
                    $this->file_name->saveAs( $this->DIR().$this->id_page.'.'.$this->file_name->extension);
                    $this->file_name = $this->id_page.'.'.$this->file_name->extension;
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
}
