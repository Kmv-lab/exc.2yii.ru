<?php


namespace app\controllers;


use app\modules\adm\models\Excursions;
use app\widgets\ExcursionsWidget;
use Yii;
use yii\web\Controller;

class ExcursionsController extends Controller
{

    public function actionExcursions(){

        return $this->render('excursions');

    }

    public function actionMore_exc($lasting_exc){

        $result = ExcursionsWidget::widget([
            'quantityExc' => Yii::$app->params['added_excursion_items_on_excursions'],
            'isAjax' => true,
            'lastingExc' => $lasting_exc
        ]);

        //vd($result);

        return $result;

    }

}