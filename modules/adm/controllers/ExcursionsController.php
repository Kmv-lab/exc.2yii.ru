<?php

namespace app\modules\adm\controllers;

use app\commands\ImagickHelper;
use app\modules\adm\models\ExcursionAdvices;
use app\modules\adm\models\ExcursionOptions;
use app\modules\adm\models\ExcursionPhotos;
use app\modules\adm\models\ExcursionPrices;
use app\modules\adm\models\Excursions;
use app\modules\adm\models\ExcursionTimetable;
use app\modules\adm\models\Guides;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\UploadedFile;

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
        $guides = (new Query())
            ->select(['id', 'name'])
            ->from('guides')
            ->all();

        if((Yii::$app->request->isPost) && (isset($_POST['ExcursionAdvices']))){
            $post = Yii::$app->request->post();
            $post = $post['ExcursionAdvices'];
            foreach ($post as $key=>$value){
                if($value){
                    $newAdvice = ExcursionAdvices::find()->where(['id_exc' => $idExc, 'id_adv' => $key])->one();
                    if($newAdvice)
                        continue;
                    $newAdvice = new ExcursionAdvices();
                    $newAdvice->id_exc = $idExc;
                    $newAdvice->id_adv = $key;
                    $newAdvice->save();
                }
                else{
                    $newAdvice = ExcursionAdvices::find()->where(['id_exc' => $idExc, 'id_adv' => $key])->one();
                    if($newAdvice)
                        $newAdvice->delete();
                }
                unset($newAdvice);
            }
        }

        if((Yii::$app->request->isPost) && (isset($_POST['ExcursionOptions']))){
            $post = Yii::$app->request->post();
            $post = $post['ExcursionOptions'];
            foreach ($post as $key=>$value){
                if($value){
                    $newOption = ExcursionOptions::find()->where(['id_exc' => $idExc, 'id_option' => $key])->one();
                    if($newOption)
                        continue;
                    $newOption = new ExcursionOptions();
                    $newOption->id_exc = $idExc;
                    $newOption->id_option = $key;
                    $newOption->save();
                }
                else{
                    $newOption = ExcursionOptions::find()->where(['id_exc' => $idExc, 'id_option' => $key])->one();
                    if($newOption)
                        $newOption->delete();
                }
                unset($newOption);
            }
        }

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
            $model->save();
        }

        $optionsArray = ExcursionOptions::find()->asArray()->where(['id_exc' => $idExc])->all();
        $options = new ExcursionOptions();

        foreach ($optionsArray as $key=>$value){
            $options->{$value['id_option']} = 1;
        }

        $advicesArray = ExcursionAdvices::find()->asArray()->where(['id_exc' => $idExc])->all();
        $advices = new ExcursionAdvices();

        foreach ($advicesArray as $key=>$value){
            $advices->{$value['id_adv']} = 1;
        }

        //vd($advices);

        return $this->render('update', [
            'model' => $model,
            'guides' => $this->convertDataToDDArray($guides),
            'advicesModel' => $advices,
            'optionsModel' => $options
        ]);
    }

    public function actionDelete($idExc){
        $model = Excursions::findOne($idExc);

        if(isset($model->main_photo))
            $model->deleteOldPhoto('main_photo');
        if(isset($model->map))
            $model->deleteOldPhoto('map');

            $model->delete();

        return $this->redirect(['index']);
    }

    public function actionAll_photos($idExc){

        $model = new ExcursionPhotos();
        $uploadSuccess = false;

        if (Yii::$app->request->isPost) {
            $model->name = UploadedFile::getInstances($model, 'name');
            if ($filesName = $model->upload()) {
                $uploadSuccess = true;

                foreach ($filesName as $value){
                    $modelForUpload = new ExcursionPhotos();
                    $modelForUpload->id_exc = $idExc;
                    $modelForUpload->name = $value;


                    $modelForUpload->save(false);

                    $this->createThumbOfImage($modelForUpload, 'name', 'resolution_excursion_photo');
                }
            }
        }

        $photos = ExcursionPhotos::find()->where(['id_exc' => $idExc])->all();

        //vd($photos);

        return $this->render('allPhotos', [
            'photos' => $photos,
            'idExc' => $idExc,
            'model' => $model,
            'uploadSuccess' => $uploadSuccess,
        ]);
    }

    public function actionAjaxcreatethumb($idExc, $name, $model=null){

        $this->enableCsrfValidation = false;

        if(!$model){
            $model = Excursions::findOne($idExc);
        }
        else{
            $model = ExcursionPhotos::findOne($_POST['id']);
        }

        return json_encode(ImagickHelper::Thumb($_POST, $model, $name));
    }

    public function actionDelete_photo($idExc, $idPhoto){
        $model = ExcursionPhotos::findOne($idPhoto);
        $model->deletePhoto();
        $model->delete();

        return $this->redirect(['all_photos', 'idExc' => $idExc]);
    }

    public function actionPrices($idExc){
        $model = new ExcursionPrices();

        if(Yii::$app->request->isPost){
            if(isset(Yii::$app->request->post()['ExcursionPrices']['id'])){
                $idPrice = Yii::$app->request->post()['ExcursionPrices']['id'];
                $existingPrice = ExcursionPrices::find()->where(['id' => $idPrice])->one();
                if($existingPrice->load(Yii::$app->request->post())){
                    $existingPrice->save();
                }
            }
            else {
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->validate()) {
                        $model->id_exc = $idExc;
                        $model->save();
                    }
                }
            }
        }

        $prices = ExcursionPrices::find()->where(['id_exc' => $idExc])->all();

        return $this->render('prices', [
            'model' => $model,
            'prices' => $prices,
            'idExc' => $idExc
        ]);
    }

    public function actionDelete_price($idPrice, $idExc){
        $existingPrice = ExcursionPrices::find()->where(['id' => $idPrice])->one();
        if(!empty($existingPrice)){
            $existingPrice->delete();
        }
        return $this->redirect(['prices', 'idExc' => $idExc]);
    }

    public function actionTimetable($idExc){


        if(Yii::$app->request->isPost){
            if(isset(Yii::$app->request->post()['ExcursionTimetable']['id'])){
                $idTimetable = Yii::$app->request->post()['ExcursionTimetable']['id'];
                $timetableNewData = ExcursionTimetable::find()->where(['id' => $idTimetable])->one();
                if ($timetableNewData->load(Yii::$app->request->post())){
                    $timetableNewData->save();
                    unset($timetableNewData);
                }
            }
            else{
                $model = new ExcursionTimetable();
                if ($model->load(Yii::$app->request->post()) && $model->validate()){
                    $model->id_exc = $idExc;
                    $model->save();
                    unset($model);
                }
            }
        }

        $model = new ExcursionTimetable();
        $timetable = ExcursionTimetable::find()->where(['id_exc' => $idExc])->all();
        $ddlIcons = $model->getIcons();

        return $this->render('timetable', [
            'idExc' => $idExc,
            'timetables' => $timetable,
            'newModel' => $model
        ]);
    }

    public function actionDelete_timetable($idTimetable, $idExc){
        $model = ExcursionTimetable::find()->where(['id' => $idTimetable])->one();

        if(!empty($model))
            $model->delete();

        return $this->redirect(['timetable', 'idExc' => $idExc]);
    }

    public function createThumbOfImage($model, $key, $resolution='resolution_main_excursion_photo'){
        $resolutions = explode("x", Yii::$app->params[$resolution]);

        $post = [
            'id' => $model->id,
            'x1' => '0',
            'y1' => '0',
            'x2' => $resolutions[0],
            'y2' => $resolutions[1],
            'r' => Yii::$app->params[$resolution]
        ];

        ImagickHelper::Thumb($post, $model, $key);
    }

    public function convertDataToDDArray($data){
        foreach ($data as $value){
            $array[$value['id']] = $value['name'];
        }
        return $array;
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