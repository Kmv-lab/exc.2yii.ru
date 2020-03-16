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

                        $this->createThumbOfImage($model, $key);
                    }
                }
                elseif ($key == 'video_src'){

                    $result = $this->getIdYouTubeVideo($value);

                    $model->$key = $result;
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
        $model = Excursions::findOne($idExc);
        if($model->load(Yii::$app->request->post())){
            $model->video_src = $this->getIdYouTubeVideo($model->video_src);
            if ($file = $model->upload('main_photo')){
                $model->main_photo = $file;
                $this->createThumbOfImage($model, 'main_photo');
            }
            else{
                $model->main_photo = $model->oldAttributes['main_photo'];
            }

            if($file = $model->upload('map')){
                $model->map = $file;
                $this->createThumbOfImage($model, 'map');
            }
            else{
                $model->map = $model->oldAttributes['map'];
            }

            //vd($model->oldAttributes);

            $model->save();

            //vd($model);
        }


        return $this->render('update', ['model' => $model]);
    }

    public function actionAjaxcreatethumb($idExc, $name){
        $this->enableCsrfValidation = false;

        $model = Excursions::findOne($idExc);

        return json_encode(ImagickHelper::Thumb($_POST, $model, $name));
    }

    public function createThumbOfImage($model, $key){
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

    public function getIdYouTubeVideo($content){
        if (strrpos($content, "ch?v=")){
            $result = substr($content, strrpos($content, "ch?v=")+5, 11);
        }
        elseif (strrpos($content, "u.be/")){
            $result = substr($content, strrpos($content, "u.be/")+5, 11);
        }
        elseif (strrpos($content, "mbed/")){
            $result = substr($content, strrpos($content, "mbed/")+5, 11);
        }
        else{
            $result = '';
        }

        return $result;
    }
}