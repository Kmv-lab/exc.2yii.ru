<?php


namespace app\modules\adm\controllers;


use app\modules\adm\models\AdmInfo;
use Yii;
use yii\web\Controller;

class InfoController extends Controller
{
    public function actionIndex(){

        $isAdmin = $this->isAdmin();
        $newModel = new AdmInfo();

        if ($post = Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($newModel->load($post)){
                $newModel->name = $post['AdmInfo']['name'];
                $newModel->content = $post['AdmInfo']['content'];
                $newModel->desc = $post['AdmInfo']['desc'];
                $newModel->is_active = 1;

                $newModel->save();
            }
        }

        $model = AdmInfo::find()->where(['is_active' => 1])->all();

        return $this->render('index', ['model' => $model, 'isAdmin' => $isAdmin, 'newModel' => $newModel]);
    }

    public function isAdmin(){
        if(Yii::$app->user->identity->getAccess('system'))
           return true;
        return false;
    }
}