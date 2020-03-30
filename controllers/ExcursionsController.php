<?php


namespace app\controllers;


use app\commands\PagesHelper;
use app\modules\adm\models\Excursions;
use app\modules\adm\models\Page;
use app\widgets\ExcursionsWidget;
use Yii;
use yii\web\Controller;

class ExcursionsController extends Controller
{

    public function actionExcursions(){

        $alias = Yii::$app->request->pathInfo;

        $alias = str_replace('/', '', $alias);

        $pages = Page::find()->where(["page_alias" => $alias])->asArray()->all();

        Yii::$app->params['breadcrumbs'] = PagesHelper::generateBreadcrumbs($pages);

        return $this->render('excursions', ['page' => $pages]);

    }

    public function actionMore_exc($lasting_exc){

        $result = ExcursionsWidget::widget([
            'quantityExc' => Yii::$app->params['added_excursion_items_on_excursions'],
            'isAjax' => true,
            'onlyElem' => true,
            'lastingExc' => $lasting_exc
        ]);
        $itsAll = false;

        $resultData = Excursions::find()->where([])->asArray()->offset($lasting_exc + Yii::$app->params['added_excursion_items_on_excursions'])->all();
        if (empty($resultData))
            $itsAll = true;

        $arrayNewBlocksAndStanding = [
            'res' => $itsAll,
            'code' => htmlspecialchars_decode($result)
        ];

        $result = json_encode($arrayNewBlocksAndStanding);


        return $result;

    }

}