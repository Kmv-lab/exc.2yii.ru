<?php


namespace app\widgets;


use app\models\ContactForm;
use yii\base\Widget;

class FormCallManager extends Widget
{

    public $model;

    public $h2Text;

    public function run(){

        $text = isset($this->h2Text) ? $this->h2Text : 'Желаете отправится на экскурсию с нами?';

        if(!$this->model){
            $this->model = new ContactForm();
        }

        return $this->render('formCallManager', ['model' => $this->model, 'text' => $text]);
    }

}