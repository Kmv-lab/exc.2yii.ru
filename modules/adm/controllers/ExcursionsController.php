<?php

namespace app\modules\adm\controllers;

use app\commands\ImagickHelper;
use app\models\SansPrev;
use app\modules\adm\models\Excursions;
use app\modules\adm\models\GPhotoSanatoriums;
use Yii;
use yii\web\Controller;

class ExcursionsController extends Controller
{

    public function actionIndex(){

        if(\Yii::$app->request->isPost){
            /*vd(Yii::$app->request->post(), false);
            vd($_POST);*/
        }

        //dsd

        $model = new Excursions();

        //vd($model);

        if ($post = Yii::$app->request->post()) {
            foreach ($post['Excursions'] as $key => $value) {
                if (($key == 'main_photo') && ($value == '')){
                    if($file = $model->upload('main_photo')){
                        $model->main_photo = $file;

                        $resolutions = explode("x", Yii::$app->params['resolution_main_excursion_photo']);

                        $post = [
                            'id' => $model->id,
                            'x1' => '0',
                            'y1' => '0',
                            'x2' => $resolutions[0],
                            'y2' => $resolutions[1],
                            'r' => Yii::$app->params['resolution_main_excursion_photo']
                        ];

                        ImagickHelper::Thumb($post, $model, 'main_photo');
                    }
                }
                elseif ($key == 'map'){
                    if($file = $model->upload('map')){
                        $model->map = $file;
                    }
                }
                else{
                    $model->$key = $value;
                }
            }
            $model->save();
        }

        return $this->render('index', ['model' => $model]);
    }

    public function actionAjaxcreatethumb(){
        $this->enableCsrfValidation = false;

        //vd(Yii::$app->request->post());
        if (isset($_POST['is_main_excursion_photo'])){
            $model = Excursions::findOne($_POST['id']);
        }

        return json_encode(ImagickHelper::Thumb($_POST, $model));
    }

}