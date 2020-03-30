<?php


namespace app\widgets;



use app\commands\helpers;
use app\modules\adm\models\Block as BlockModel;
use yii\base\Widget;



class Staff extends Widget
{
    public $nameOfStaff;

    public $id;

    public function run()
    {
        $nameOfStaff = 'app\modules\adm\models\\'."$this->nameOfStaff";

        $model = $nameOfStaff::find()->all() ? $nameOfStaff::find()->all() : false;

        if ($model)
            return $this->render('staff', ['elems' => $model, 'nameOfStaff' => $this->nameOfStaff]);

        return false;
    }

}