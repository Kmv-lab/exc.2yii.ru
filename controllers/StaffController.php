<?php


namespace app\controllers;



use app\commands\PagesHelper;
use app\models\ContactForm;
use Yii;
use yii\web\Controller;

class StaffController extends  Controller
{

    public function actionGuides(){

        $model = new ContactForm();

        $paramsPage = $this->getPageInfo();

        return $this->render('guides', ['paramsPage' => $paramsPage, 'model' => $model]);
    }

    public function actionDrivers(){

        $model = new ContactForm();

        $paramsPage = $this->getPageInfo();

        return $this->render('drivers', ['paramsPage' => $paramsPage, 'model' => $model]);
    }

    private function getPageInfo(){
        $urlArr = explode('/',Yii::$app->request->pathInfo);//массив родительских страниц

        array_pop($urlArr);//удаление последнего элемента массива, он пуст.

        $okURL  = PagesHelper::getPagesInUrl($urlArr);
        Yii::$app->params['breadcrumbs'] = PagesHelper::generateBreadcrumbs($okURL);

        $pageParam = [];

        foreach ($okURL as $key=>$value){
            if ($value['page_alias'] == $urlArr[0]){
                $pageParam = $okURL[$key];
            }
        }

        if (empty($pageParam)){
            die("Критическая ошибка. Обезьянки уже трудяться.");
        }

        return $pageParam;
    }

}