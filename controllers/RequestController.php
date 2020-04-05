<?php


namespace app\controllers;


use app\models\ContactForm;
use Yii;
use yii\web\Controller;

class RequestController extends Controller
{

    public function actionManager_form(){
        $model = new ContactForm();

        if(Yii::$app->request->isAjax){
            if ($model->load(Yii::$app->request->post())){
                if($model->validate()){
                    return '
                        <div id="request-response">
                            <span style="color: green; font-size: 25px">ОТПРАВЛЕНО!!!</span>
                        </div>
                    ';
                }
            }
        }

        return '
                        <div id="request-response">
                            <span style="color: red; font-size: 25px">ПРОВЕРЬТЕ ПРАВИЛЬНОСТЬ ДАННЫХ!</span>
                        </div>
                    ';
    }

}