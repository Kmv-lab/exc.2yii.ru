<?php

namespace app\controllers;

use app\commands\helpers;
use Yii;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\web\Controller;
use app\commands\PagesHelper;
use yii\web\NotFoundHttpException;


class NewsController extends Controller
{
    public $layout = 'page_full';
    public $newsUrl = ['news'];
    public $type_pages = [1=>58,56,61];

    public function actionNews($page=1)
    {
        $page_arr = Yii::$app->params['pages'][$this->type_pages[1]];
        return $this->render('index', $this->index($page_arr, $page, 1));
    }
    public function actionBlogs($page=1)
    {
        $page_arr = Yii::$app->params['pages'][$this->type_pages[2]];
        return $this->render('index', $this->index($page_arr, $page, 2));
    }
    public function actionArticles($page=1)
    {
        $page_arr = Yii::$app->params['pages'][$this->type_pages[3]];
        return $this->render('index', $this->index($page_arr, $page, 3));
    }

    function index($page_arr, $page, $type){
        if(!empty($page_arr['seo_title']))
            $page_arr['seo_title'] .= ' - Страница '.$page;
        helpers::createSEO($page_arr, $page_arr['page_name'].' - Страница '.$page, $page_arr['page_name']);
        Yii::$app->params['breadcrumbs'][] = ['label' => empty(Yii::$app->params['pages'][55]['page_breadcrumbs_name'])
            ? Yii::$app->params['pages'][55]['page_menu_name'] : Yii::$app->params['pages'][55]['page_breadcrumbs_name'], 'url'=>PagesHelper::getUrlById(55)];
        Yii::$app->params['breadcrumbs'][] = ['label' => empty($page_arr['page_breadcrumbs_name']) ? $page_arr['page_menu_name'] : $page_arr['page_breadcrumbs_name']];

        $request = $this->get_all($type,false,$page);
        $news = Yii::$app->db->createCommand($request['sql'])->bindValues($request['params'])->queryAll();
        $request = $this->get_all($type);
        $count = Yii::$app->db->createCommand($request['sql'])->bindValues($request['params'])->queryScalar();
        return ['news'=>$news, 'page'=>$page, 'count'=>$count, 'type'=>$type];
    }

    function get_all($type, $count = true, $page = 1){//page == 0 выберет все без пагинации
        $params = [];
        $limit = '';
        if(!$count && $page != 0){
            if($type == 1)
                $count_item = (int)Yii::$app->params['count_news_items'];
            elseif($type == 2)
                $count_item = (int)Yii::$app->params['count_blogs_items'];
            elseif($type == 3)
                $count_item = (int)Yii::$app->params['count_art_items'];
            $limit = ' LIMIT '.($page-1)*$count_item.','.$count_item;
        }
        $where = ' WHERE is_active = 1 AND type = '.(int)$type;
        $order = ' ORDER BY date_publication DESC';
        $select = $count ? 'count(id_news)' :'*';
        $SQL = 'SELECT '.$select.'
                FROM news'.$where.$order.$limit;
        return ['sql'=>$SQL, 'params'=>$params];
    }

    public function actionNew($alias){
        return $this->render('detail', ['new'=>$this->detail($alias,1)]);
    }
    public function actionBlog($alias){
        return $this->render('detail', ['new'=>$this->detail($alias,2)]);
    }
    public function actionArticle($alias){
        return $this->render('detail', ['new'=>$this->detail($alias,3)]);
    }

    /** create breadcrumbs for dynamic page
     * @param $alias string
     * @param $type int type genereting page(news, blog,...)
     *
     * @return array breadcrumbs
     * */
    public function detail($alias, $type){

        $SQL = 'SELECT * FROM news WHERE is_active=1 AND alias = :alias AND type = :type';
        $new = Yii::$app->db->createCommand($SQL)->bindValues([':alias'=>$alias, ':type'=>$type] )->queryOne();

        if (empty($new)){
            throw new NotFoundHttpException('Страница не найдена');
        }

        helpers::createSEO($new, $new['name'], $new['name']);

        //как же плохо тут выглядет сокращённый if. Не надо так. Трудно разобрать.

        Yii::$app->params['breadcrumbs'][] = ['label' => empty(Yii::$app->params['pages'][55]['page_breadcrumbs_name'])
            ? Yii::$app->params['pages'][55]['page_menu_name'] : Yii::$app->params['pages'][55]['page_breadcrumbs_name'],
            'url'=>PagesHelper::getUrlById(55)];

        $page_news = Yii::$app->params['pages'][$this->type_pages[$type]];

        Yii::$app->params['breadcrumbs'][] = ['label' => empty($page_news['page_breadcrumbs_name']) ?
            $page_news['page_menu_name'] : $page_news['page_breadcrumbs_name'], 'url'=>PagesHelper::getUrlById($this->type_pages[$type])];

        Yii::$app->params['breadcrumbs'][] = ['label' => $new['name']];
        
        return  $new;
    }
}