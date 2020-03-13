<?php

namespace app\modules\adm\controllers;

use app\modules\adm\models\Excursions;
use yii\web\Controller;

class ExcursionsController extends Controller
{

    public function actionIndex(){

        $model = new Excursions();

        //vd($model);

        return $this->render('index', ['model' => $model]);
    }

}