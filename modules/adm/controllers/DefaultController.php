<?php

namespace app\modules\adm\controllers;

use yii\web\Controller;
use app\commands\helpers;

/**
 * Default controller for the `adm` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        helpers::createSeo('', 'Админка', '');
        $dataForTest = "Привет, как интересно...";
        return $this->render('index', [
            'dataForTest' => $dataForTest,
        ]);
    }

    public function actionAjaxtranslite($str)
    {
        $str = helpers::translit($str);
        return $str;
    }
}
