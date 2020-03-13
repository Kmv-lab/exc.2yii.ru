<?php

namespace app\controllers;

use app\models\Pages_ext;
use app\models\Services;
use yii\web\HttpException;
use yii\helpers\Url;
use Yii;
use yii\helpers\ArrayHelper;
use app\helpers\D;

class ServicesController extends MainController
{
    public $layout = 'page';
    public $pageUrl = ['services'];

    public function actionIndex($pagination='1')
    {
        $this->okURL = $this->getPagesInUrl($this->pageUrl,$this->pages);
        $this->currentPage = $this->okURL[count($this->okURL)-1];
        $currentPage = $this->currentPage;

        $id = $this->id;
        $SQL = 'SELECT * FROM pages_ext';
        $page_ext = Pages_ext::findBySql($SQL)->where(['id_page'=> $id])->asArray()->all();
        $this->currentPage = ArrayHelper::merge($currentPage,$page_ext);

        $this->createSEO($currentPage,$currentPage['page_menu_name'],$currentPage['page_menu_name'],'','');
        $SQL = 'SELECT * FROM services WHERE is_active = 1 ORDER BY priority ASC,id_service';
        $services = Services::findBySql($SQL)->asArray()->all();
        $breadcrumbs_name = empty($currentPage['page_breadcrumbs_name']) ? $currentPage['page_menu_name'] : $currentPage['page_breadcrumbs_name'];
        $breadcrumbs[] = ['url'=>Url::to('site/index'), 'name'=>'БасСистема',];
        $breadcrumbs[] = ['name'=>$breadcrumbs_name];
        $this->breadcrumbs = $breadcrumbs;

        return $this->render('index', compact('services','currentPage', 'breadcrumbs'));
    }

    public function actionDetail($alias){
        $this->layout = 'page_service';

        $SQL = 'SELECT * FROM services WHERE alias = :alias AND is_active = 1';
        $service = Yii::$app->db->createCommand($SQL)->bindValue(':alias', $alias)->queryOne();

        if(empty($service))
            throw new HttpException(404);

        $this->createSEO($service,$service['name'],$service['name'],'','');

        $this->okURL = $this->getPagesInUrl($this->pageUrl,$this->pages);
        $this->currentPage = $this->okURL[count($this->okURL)-1];
        $currentPage = $this->currentPage;

        $breadcrumbs_name = empty($currentPage['page_breadcrumbs_name']) ? $currentPage['page_menu_name'] : $currentPage['page_breadcrumbs_name'];
        $breadcrumbs_name2 = empty($service['breadcrumbs_name']) ? $service['name'] : $service['breadcrumbs_name'];
        $breadcrumbs[] = ['url'=>Url::toRoute('site/index'), 'name'=>'БасСистема',];
        $breadcrumbs[] = ['url'=>Url::toRoute('services/index'), 'name'=> $breadcrumbs_name];
        $breadcrumbs[] = ['name'=>$breadcrumbs_name2];
        $this->breadcrumbs = $breadcrumbs;


        return $this->render('detail', compact('service', 'breadcrumbs'));

    }
}