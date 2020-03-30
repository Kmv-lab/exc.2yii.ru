<?php


namespace app\modules\adm\controllers;

use app\modules\adm\models\Main_page;
use yii\web\Controller;
use yii;


class Test_new_strController extends Controller{
    
    public $model;

    public function actionIndex(){

        $emptyModel = new Main_page();

        if((Yii::$app->request->isPost) && !isset(Yii::$app->request->post()['Main_page']['id'])){
            //vd(Yii::$app->request->post());
            if ($emptyModel->load(Yii::$app->request->post())){
                //vd(Yii::$app->request->post());
                $emptyModel->save();

                unset($emptyModel);
            }
        }
        else if((Yii::$app->request->isPost) && isset(Yii::$app->request->post()['Main_page']['id'])){
            $model = Main_page::find()->where(['id' => Yii::$app->request->post()['Main_page']['id']])->one();
            if($model->load(Yii::$app->request->post())){
                $model->save();
            }
        }

        $this->model = Main_page::find()->orderBy('priority')->all();
        $emptyModel = new Main_page();

        return $this->render('index', [ "model" => $this->model, 'emptyModel' => $emptyModel]);
    }

    public function actionDelete($idBlock){
        $model = Main_page::find()->where(['id' => $idBlock])->one();
        if (!empty($model))
            $model->delete();

        return $this->redirect(['index']);
    }

    private function processingData($ids){
            
        $request = Yii::$app->request;
        $post = $request->post();

        if( !empty($post) && $post['Main_page']['type']!=3){//костыльная проверка типа, я знаю всё очень плохо. Потом нужно поправить

            $newData = $this->model[array_search($post['Main_page']['id'], $ids)];

            $newData->content = $post['Main_page']['content'];

            $result = $newData->save() ? true : false;
        }
        else if( !empty($post) && $post['Main_page']['type']==3 ){

            $newData = $this->model[array_search($post['Main_page']['id'], $ids)];
            $currentImageName = $newData->content;
            $newData->curType = $post['Main_page']['type'];
            $fileName = $newData->upload();
            $newData->content = $fileName;

            if ($newData->save()){
                $newData->deleteOldImage($currentImageName);
                $result = true;
            }
            else{
                $result = false;
            }
        }
        return $result;
    }
}