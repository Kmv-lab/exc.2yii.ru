<?php


namespace app\controllers;


use app\commands\PagesHelper;
use app\modules\adm\models\ExcursionAdvices;
use app\modules\adm\models\ExcursionComments;
use app\modules\adm\models\ExcursionOptions;
use app\modules\adm\models\ExcursionPhotos;
use app\modules\adm\models\ExcursionPrices;
use app\modules\adm\models\Excursions;
use app\modules\adm\models\ExcursionTimetable;
use app\modules\adm\models\Guides;
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

    public function actionExcursion($alias){
        $excursion = Excursions::find()->where(['alias' => $alias])->one();

        $prices = ExcursionPrices::find()->where(['id_exc' => $excursion->id])->all();
        if (!empty($prices)){
            if (count($prices) > 1){
                $timeNow = strtotime('now');
                foreach ($prices as $price){
                    if((strtotime($price->start) < $timeNow) && ($timeNow < strtotime($price->end))){
                        $priceExc = $price;
                    }
                }
            }
            elseif (count($prices) == 1){
                $priceExc = $prices[0];
            }
        }
        else{
            $priceExc = 'Пока не указано';
        }

        $photos = ExcursionPhotos::find()->where(['id_exc' => $excursion->id])->all();

        $timetable = ExcursionTimetable::find()->where(['id_exc' => $excursion->id])->orderBy('time')->all();

        $guide = Guides::find()->where(['id' => $excursion->id_guide])->one();

        $options = ExcursionOptions::find()->where(['id_exc' => $excursion->id])->all();

        $advices = ExcursionAdvices::find()->where(['id_exc' => $excursion->id])->all();

        $comments = ExcursionComments::find()->where(['id_exc' => $excursion->id])->all();

        return $this->render('excursion', [
            'excursion' => $excursion,
            'price' => $priceExc,
            'photos' => $photos,
            'timetable' => $timetable,
            'guide' => $guide,
            'options' => $options,
            'advices' => $advices,
            'comments' => $comments
        ]);
    }

}