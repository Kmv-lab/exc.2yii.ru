<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\commands\PagesHelper;
use yii\helpers\Url;

class Sitemap extends Model{



    //public $siteUrl = Url::home(true);

    public function getUrl(){
        $urls = [];
        $url_rules = [];
        //Получаем массив URL из таблицы Sef
        $pages = PagesHelper::select(Yii::$app->params['pages'],[],[['attr_name'   =>  'is_active',   'operand'=> '=', 'value'=> '1'],
            ['attr_name'   =>  'show_sitemap',   'operand'=> '=', 'value'=> '1']]);

        //'shini_legko/all_sizes/R-<radius:\d+>'  => 'shini/radius',//все товары с выбраным радиусом
        $SQL = 'SELECT radius FROM tyres_radius WHERE is_passenger = 1';
        $tyres_radius = Yii::$app->db->createCommand($SQL)->queryColumn();
        foreach ($tyres_radius AS $tr){
            $tr = str_replace('.00','',(string)$tr);
            $url_rules[] = Url::to(['shini/radius', 'radius'=>$tr]);
        }
        unset($tyres_radius);

        //'shini_legko/all_sizes/R-<radius:\d+>/<width:\d+>-<profile:\d+>' =>
        //                    'shini/size',//все товары с выбраным размером
        $SQL = 'SELECT size_width, size_profile, radius
              FROM tyres_sizes 
              JOIN tyres_radius ON tyres_radius.id = tyres_sizes.id_radius
              WHERE tyres_radius.is_passenger = 1';
        $tyres_sizes = Yii::$app->db->createCommand($SQL)->queryAll();
        foreach ($tyres_sizes AS $ts){
            $url_rules[] = Url::to(['shini/size',
                'radius'=>str_replace('.00','',(string)$ts['radius']),
                'width'=>$ts['size_width'],
                'profile'=>str_replace('.00','',(string)$ts['size_profile'])]);
        }
        unset($tyres_sizes);

        //'shini_legko/producers/<alias:\S+>'     => 'shini/producer',//все шины одного производителя
        //'shini_gruz/producers/<alias:\S+>'     => 'shini/gruz_producer',//все шины одного производителя
        $SQL = 'SELECT alias, is_truck, is_passenger FROM tyres_vendors';
        $tyres_vendors = Yii::$app->db->createCommand($SQL)->queryAll();
        foreach ($tyres_vendors AS $tyres_vendor){
            if($tyres_vendor['is_passenger'])
                $url_rules[] =
                    Url::to(['shini/producer',
                    'alias'=>$tyres_vendor['alias']]);
            if($tyres_vendor['is_truck'])
                $url_rules[] =
                    Url::to(['shini/gruz_producer',
                        'alias'=>$tyres_vendor['alias']]);

        }
        unset($tyres_vendors);

        //'shini_legko/producers/<vendor:\S+>/<model:\S+>' => 'shini/model_tyre',//модель шины
        //'shini_gruz/producers/<vendor:\S+>/<model:\S+>' => 'shini/gruz_model_tyre',//модель шины
        $SQL = 'SELECT type, tyres_models.alias, tyres_vendors.alias AS vendor_alias FROM tyres_models
                JOIN tyres_vendors ON tyres_vendors.id_vendor = tyres_models.id_vendor';
        $tyres_models = Yii::$app->db->createCommand($SQL)->queryAll();
        foreach ($tyres_models AS $tyres_model){
            if($tyres_model['type'] < 6)
                $url_rules[] =
                    Url::to(['shini/model_tyre',
                        'vendor'=>$tyres_model['vendor_alias'],
                        'model'=>$tyres_model['alias']]);
            else
                $url_rules[] =
                    Url::to(['shini/gruz_model_tyre',
                        'vendor'=>$tyres_model['vendor_alias'],
                        'model'=>$tyres_model['alias']]);
        }
        unset($tyres_models);

        //'shini_legko/producers/<vendor:\S+>/<model:\S+>/<id:\d+>' => 'shini/tyre',//шина
        //'shini_gruz/producers/<vendor:\S+>/<model:\S+>/<id:\d+>' => 'shini/gruz_tyre',//шина
        $SQL = 'SELECT type, tyres_models.alias AS model_alias, tyres_vendors.alias AS vendor_alias, id_size 
                FROM tyres_model_sizes
                JOIN tyres_models ON tyres_models.id_model = tyres_model_sizes.id_model
                JOIN tyres_vendors ON tyres_vendors.id_vendor = tyres_models.id_vendor
                WHERE tyres_model_sizes.price > 0';
        $tyres_model_sizes = Yii::$app->db->createCommand($SQL)->queryAll();
        foreach ($tyres_model_sizes AS $tyres_model_size){
            if($tyres_model_size['type'] < 6)
                $url_rules[] =
                    Url::to(['shini/tyre',
                        'vendor'=>$tyres_model_size['vendor_alias'],
                        'model'=>$tyres_model_size['model_alias'],
                        'id'=>$tyres_model_size['id_size']]);
            else
                $url_rules[] =
                    Url::to(['shini/gruz_tyre',
                        'vendor'=>$tyres_model_size['vendor_alias'],
                        'model'=>$tyres_model_size['model_alias'],
                        'id'=>$tyres_model_size['id_size']]);
        }
        unset($tyres_model_sizes);

        //'shini_legko/vendors_auto/<alias:\S+>'  => 'shini/vendor_auto', //все модели текущей марки авто
        //'wheels/vendors_auto/<alias:\S+>'=> 'wheels/vendor_auto', //все модели текущей марки авто
        $SQL = 'SELECT alias FROM vehicles_vendors';
        $vehicles_vendors = Yii::$app->db->createCommand($SQL)->queryColumn();
        foreach ($vehicles_vendors AS $vehicles_vendor){
            $url_rules[] =
                Url::to(['shini/vendor_auto',
                    'alias'=>$vehicles_vendor,]);
            $url_rules[] =
                Url::to(['wheels/vendor_auto',
                    'alias'=>$vehicles_vendor,]);
        }
        unset($vehicles_vendors);

        //'wheels/R-<radius:\d+>'            => 'wheels/radius',//все товары с выбраным радиусом
        $SQL = 'SELECT radius FROM wheels_radius WHERE is_passenger = 1';
        $wheels_radius = Yii::$app->db->createCommand($SQL)->queryColumn();
        foreach ($wheels_radius AS $wr){
            $url_rules[] = Url::to(['wheels/radius', 'radius'=>$wr]);
        }
        unset($wheels_radius);

        //'wheels/producers/<alias:\S+>'     => 'wheels/producer',//все диски одного производителя
        //'wheels_gruz/producers/<alias:\S+>'     => 'wheels/gruz_producer',//все диски одного производителя
        $SQL = 'SELECT alias, is_truck, is_passenger FROM wheels_vendors';
        $wheels_vendors = Yii::$app->db->createCommand($SQL)->queryAll();
        foreach ($wheels_vendors AS $wheels_vendor){
            if($wheels_vendor['is_passenger'])
                $url_rules[] =
                    Url::to(['wheels/producer',
                        'alias'=>$wheels_vendor['alias']]);
            if($wheels_vendor['is_truck'])
                $url_rules[] =
                    Url::to(['wheels/gruz_producer',
                        'alias'=>$wheels_vendor['alias']]);
        }
        unset($wheels_vendors);

        //'wheels/producers/<vendor:\S+>/<model:\S+>' => 'wheels/model_wheel',//модель диска
        //'wheels_gruz/producers/<vendor:\S+>/<model:\S+>' => 'wheels/gruz_model_wheel',//модель диска
        $SQL = 'SELECT type, wheels_models.model_alias, wheels_vendors.alias AS vendor_alias FROM wheels_models
                JOIN wheels_vendors ON wheels_vendors.id_vendor = wheels_models.id_vendor';
        $wheels_models = Yii::$app->db->createCommand($SQL)->queryAll();
        foreach ($wheels_models AS $wheels_model){
            if($wheels_model['type'] != 2)
                $url_rules[] =
                    Url::to(['wheels/model_wheel',
                        'vendor'=>$wheels_model['vendor_alias'],
                        'model'=>$wheels_model['model_alias']]);
            else
                $url_rules[] =
                    Url::to(['wheels/gruz_model_wheel',
                        'vendor'=>$wheels_model['vendor_alias'],
                        'model'=>$wheels_model['model_alias']]);
        }
        unset($wheels_models);

        //'wheels/producers/<vendor:\S+>/<model:\S+>/<id:\d+>' => 'wheels/wheel',//диск
        //'wheels_gruz/producers/<vendor:\S+>/<model:\S+>/<id:\d+>' => 'wheels/gruz_wheel',//диск
        $SQL = 'SELECT type, wheels_models.model_alias, wheels_vendors.alias AS vendor_alias, id_size 
                FROM wheels_model_sizes
                JOIN wheels_models ON wheels_models.id_model = wheels_model_sizes.id_model
                JOIN wheels_vendors ON wheels_vendors.id_vendor = wheels_models.id_vendor
                WHERE wheels_model_sizes.price > 0';
        $wheels_model_sizes = Yii::$app->db->createCommand($SQL)->queryAll();
        foreach ($wheels_model_sizes AS $wheels_model_size){
            if($wheels_model_size['type'] != 2)
                $url_rules[] =
                    Url::to(['wheels/wheel',
                        'vendor'=>$wheels_model_size['vendor_alias'],
                        'model'=>$wheels_model_size['model_alias'],
                        'id'=>$wheels_model_size['id_size']]);
            else
                $url_rules[] =
                    Url::to(['wheels/gruz_wheel',
                        'vendor'=>$wheels_model_size['vendor_alias'],
                        'model'=>$wheels_model_size['model_alias'],
                        'id'=>$wheels_model_size['id_size']]);
        }
        unset($wheels_model_sizes);

        //'news/<alias:\S+>'                      =>  'news/new'
        $SQL = 'SELECT alias FROM news WHERE is_active = 1';
        $news = Yii::$app->db->createCommand($SQL)->queryColumn();
        foreach ($news AS $new){
            $url_rules[] = Url::to(['news/new','alias'=>$new,]);
        }
        unset($news);

        $url_rules = array_merge($url_rules, $this->getChildPages($pages,0));
        //Формируем двумерный массив. createUrl преобразует ссылки в правильный вид.
        //Добавляем элемент массива 'daily' для указания периода обновления контента
     //   foreach ($url_rules as $url_rule){
        //    $urls[] = array($url_rule,'daily');
      //  }
        return $url_rules;
    }
    public function getXml($urls){
        $host = Yii::$app->request->hostInfo; // домен сайта
        ob_start();
        echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            <url>
                <loc><?= $host ?></loc>
                <changefreq>daily</changefreq>
                <priority>1</priority>
            </url>
            <?php foreach($urls as $url): ?>
                <url>
                    <loc><?= $host.$url ?></loc>
                    <?php //<changefreq><?= $url[1] </changefreq> ?>
                </url>
            <?php endforeach; ?>
        </urlset>
        <?php return ob_get_clean();
    }
    public function showXml($xml_sitemap){
        // устанавливаем формат отдачи контента
        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        //повторно т.к. может не сработать
        header("Content-type: text/xml");
        return $xml_sitemap;
        //Yii::$app->end();
    }
    public function getChildPages($pages,$id_parent)
    {
        $domain     = Yii::$app->request->hostInfo;

        $array = [];
        foreach($pages AS $page)
        {
            if($page['id_parent'] == $id_parent){
                $array[] = PagesHelper::getUrlById($page['id_page']);
                $array = array_merge($array, $this->getChildPages($pages, $page['id_page']));
            }
        }
        return $array;
    }


    //______________________________________________________________________________________________________________________________________

    public $arrayOfDynamicPages=[
            [
                "type"=> "1",
                "page"=> "o-nas/news/"
            ],
            [
                "type"=> "2",
                "page"=> "o-nas/blog/"
            ],
            [
                "type"=> "3",
                "page"=> "o-nas/articles/"
            ],
    ];

    public function getStatickPages(){
        $SQL = 'SELECT id_page, id_parent_page, page_alias FROM pages WHERE is_active = 1';
        $statickPages = Yii::$app->db->createCommand($SQL)->queryAll();

        $arrPages=[];

        foreach ($statickPages as $value){
            $arrPages[$value['id_page']]=[
                    'id_parent_page' => $value['id_parent_page'],
                    'page_alias' => $value['page_alias']
            ];
        }

        return $arrPages;
    }

    public function getDynamicPages(){

        $SQL = 'SELECT type, alias FROM news WHERE is_active = 1';
        $dynamicPages = Yii::$app->db->createCommand($SQL)->queryAll();

        $arrPages=[];

        foreach ($dynamicPages as $dynamicPage){
            $arrPages[$dynamicPage['type']][]=[
                    "alias" => $dynamicPage['alias']
            ];
        }

        return $arrPages;
    }

}