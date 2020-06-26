<?php


namespace app\widgets;


use app\models\ContactForm;
use app\models\ExcFilter;
use yii\base\Widget;

class ExcursionFilter extends Widget
{

    public $model = false;

    public function run(){

        if(!$this->model){
            $this->model = new ExcFilter();
        }

        return $this->render('excursionFilteForm', ['model' => $this->model]);
    }

}