<?php


namespace app\controllers;


use app\models\CallRequest;
use app\models\ContactForm;
use DateTime;
use Yii;
use yii\web\Controller;

class RequestController extends Controller
{

    public function actionManager_form(){
        $model = new ContactForm();

        if(Yii::$app->request->isAjax){
            if ($model->load(Yii::$app->request->post())){
                if($model->validate()){
                    $requstToDb = new CallRequest();
                    $requstToDb->page_request = $model->page;
                    $date = new DateTime('NOW');
                    $requstToDb->date_request = $date->format('Y-m-d H:i:s');
                    $requstToDb->user_name = $model->name;
                    $requstToDb->user_phone = $model->phone;
                    $requstToDb->save();

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