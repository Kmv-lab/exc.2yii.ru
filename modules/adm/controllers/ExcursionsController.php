<?php

namespace app\modules\adm\controllers;

use app\commands\ImagickHelper;
use app\modules\adm\models\Excursions;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class ExcursionsController extends Controller
{

    public function actionIndex(){

        $model = new Excursions();

        if ($post = Yii::$app->request->post()) {
            foreach ($post['Excursions'] as $key => $value) {
                if (($key == 'main_photo') || ($key == 'map')){
                    if($file = $model->upload($key)){
                        $model->{$key} = $file;

                        $resolutions = explode("x", Yii::$app->params['resolution_main_excursion_photo']);

                        $post = [
                            'id' => $model->id,
                            'x1' => '0',
                            'y1' => '0',
                            'x2' => $resolutions[0],
                            'y2' => $resolutions[1],
                            'r' => Yii::$app->params['resolution_main_excursion_photo']
                        ];

                        ImagickHelper::Thumb($post, $model, $key);
                    }
                }
                else{
                    $model->$key = $value;
                }
            }
            $model->save();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Excursions::find(),
        ]);

        return $this->render('index', ['model' => $model, 'provider' => $dataProvider]);
    }

    public function actionUpdate($idExc){
        $model = Excursions::find()->where(['id' => $idExc])->one();
        //vd($model);
        return $this->render('update', ['model' => $model]);
    }

    public function actionAjaxcreatethumb($idExc, $name){
        $this->enableCsrfValidation = false;

        $model = Excursions::findOne($idExc);

        return json_encode(ImagickHelper::Thumb($_POST, $model, $name));
    }

}