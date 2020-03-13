<?php
namespace app\widgets;

use yii\base\Widget;
use Yii;
use app\models\ContactForm;

class Contact extends Widget
{
    public $name = 'Нужна косультация? Мы вам перезвоним!';
    public $btn_name = 'Заказать звонок';
    public $wrap = true;

    public function run()
    {
        Yii::$app->params['counter_form']++;
        $model = new ContactForm();
        return $this->render('contact', [
            'model'=>$model,
            'name'=>$this->name,
            'wrap'=>$this->wrap,
            'btn_name'=>$this->btn_name]);
    }
}