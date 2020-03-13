<?php


namespace app\modules\adm\controllers;

use app\modules\adm\models\Main_page;
use yii\web\Controller;
use yii;


class Test_new_strController extends Controller{
    
    public $model;

    public function actionIndex(){
        $this->model = Main_page::find()->all();

        $ids = array_column($this->model, 'id');

        if (Yii::$app->request->isPost){

            $result = $this->processingData($ids);
        }

        $result = isset($result) ? $result : false;

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('index', [ "model" => $this->model]);
        }
        return $this->render('index', [ "model" => $this->model]);
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