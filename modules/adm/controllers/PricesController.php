<?php


namespace app\modules\adm\controllers;


use app\modules\adm\models\SanBlocks;
use Yii;
use yii\web\Controller;

class PricesController extends Controller
{

    public function actionIndex() {

        $modelForBlocks = SanBlocks::find()->where(['id_san' => 0])->orderBy('priority')->all();

        $typeOfBlock =  [
            2 => 'WYSIWYG',
            3 => 'Code-Mirror',//3
            6 => 'Цены'
        ];

        return $this->render('index', ['modelForBlock' => $modelForBlocks, 'typeOfBlock' => $typeOfBlock]);
    }

}