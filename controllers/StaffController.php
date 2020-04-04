<?php


namespace app\controllers;



use yii\web\Controller;

class StaffController extends  Controller
{

    public function actionGuides(){
        return $this->render('guides', []);
    }

    public function actionDrivers(){
        return $this->render('drivers', []);
    }

}