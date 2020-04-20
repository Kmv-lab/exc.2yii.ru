<?php


namespace app\controllers;


use app\commands\helpers;
use app\commands\PagesHelper;
use app\models\BidRequest;
use app\models\BookingForm;
use app\models\ExcFilter;
use app\models\ExcOrderForm;
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
use DateTime;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ExcursionsController extends Controller
{

    public function actionExcursions(){

        $model = new ExcFilter();

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())){
                //vd($model->attributes);
            }
        }

        SiteController::CreateSeo();

        $alias = Yii::$app->request->pathInfo;

        $alias = str_replace('/', '', $alias);

        $pages = Page::find()->where(["page_alias" => $alias])->asArray()->all();

        Yii::$app->params['breadcrumbs'] = PagesHelper::generateBreadcrumbs($pages);

        return $this->render('excursions', ['page' => $pages, 'model' => $model]);

    }

    public function actionMore_exc($lasting_exc){

        $result = ExcursionsWidget::widget([
            'quantityExc' => Yii::$app->params['added_excursion_items_on_excursions'],
            'isAjax' => true,
            'onlyElem' => true,
            'lastingExc' => $lasting_exc
        ]);
        $itsAll = false;

        if (isset(Yii::$app->params["show_button_more_exc"]) && Yii::$app->params["show_button_more_exc"])
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

        $comments = ExcursionComments::find()->where(['id_exc' => $excursion->id])->orderBy('date')->all();

        $model = new ExcOrderForm();

        return $this->render('excursion', [
            'excursion' => $excursion,
            'price' => $priceExc,
            'photos' => $photos,
            'timetable' => $timetable,
            'guide' => $guide,
            'options' => $options,
            'advices' => $advices,
            'comments' => $comments,
            'model' => $model
        ]);
    }

    public function actionBooking($alias){

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

        $oldModel = new ExcOrderForm();

        $model = new BookingForm();
        if((Yii::$app->request->isPost) && ($oldModel->load(Yii::$app->request->post()))){
            $model->date = $oldModel['date'];
            $model->price = $oldModel['price'];
            $model->price_ch = $oldModel['price_ch'];
            $model->price_pref = $oldModel['price_pref'];
        }

        if ((Yii::$app->request->isPost) && ($model->load(Yii::$app->request->post()))){
            $date = new DateTime('NOW');
            $requestBidExc = new BidRequest();
            $requestBidExc->date_request = $date->format('Y-m-d H:i:s');
            $requestBidExc->page_requst = Yii::$app->request->pathInfo;
            $requestBidExc->id_exc = $excursion->id;
            $excDate = new DateTime($model->date);
            $requestBidExc->date_exc = $excDate->format('Y-m-d H:i:s');
            $requestBidExc->ticket = $model->price;
            $requestBidExc->ticket_ch = $model->price_ch;
            $requestBidExc->ticket_pref = $model->price_pref;
            $requestBidExc->user_name = $model->personName;
            $requestBidExc->user_phone = $model->personPhone;
            $requestBidExc->user_email = $model->personEmail;
            $requestBidExc->save();
        }

        return $this->render('booking', [
            'excursion' => $excursion,
            'price' => $priceExc,
            'model' => $model
        ]);
    }

}