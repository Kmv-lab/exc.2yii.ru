<?php


namespace app\modules\adm\controllers;


use app\commands\ImagickHelper;
use app\modules\adm\models\Excursions;
use app\modules\adm\models\Guides;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class GuidesController extends Controller
{
    public function actionIndex(){
        $model = new Guides();

        $dataProvider = new ActiveDataProvider([
            'query' => Guides::find(),
        ]);

        return $this->render('index', ['model' => $this->loadAndSaveModel($model), 'provider' => $dataProvider]);
    }

    public function actionUpdate($idGuide){
        $model = Guides::findOne($idGuide);

        return $this->render('update', ['model' => $this->loadAndSaveModel($model)]);
    }

    public function actionDelete($idGuide){
        $model = Guides::findOne($idGuide);

        if(isset($model->file_name)){
            $model->deleteOldPhoto('file_name');
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    public function actionAjaxcreatethumb($idGuide, $name=null){
        $this->enableCsrfValidation = false;

        $model = Guides::findOne($idGuide);

        return json_encode(ImagickHelper::Thumb($_POST, $model, $name));
    }

    public function loadAndSaveModel($model){

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post())){
                /*if(($model->file_name) && ($file = $model->upload('file_name'))){
                    $model->file_name = $file;

                    //vd($model->attributes);

                    $this->createThumbOfImage($model, 'file_name');
                }*/

                if ($file = $model->upload('file_name')){
                    $model->file_name = $file;
                    $this->createThumbOfImage($model, 'file_name');
                }
                else{
                    $model->file_name = $model->oldAttributes['file_name'];
                }

                $model->save();
            }
        }

        return $model;
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

}