<?php

namespace app\modules\adm\controllers;

use Yii;
use app\modules\adm\models\StaticSeo;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\commands\helpers;
use yii\helpers\Url;

/**
 * Static_seoController implements the CRUD actions for StaticSeo model.
 */
class Static_seoController extends Controller
{

    /**
     * Lists all StaticSeo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => StaticSeo::find(),
        ]);

        $this->fillDopContent();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing StaticSeo model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->upload()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $this->fillDopContent($model);
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDel_icon($id){
        $model = $this->findModel($id);
        @unlink($model->DIR().$model->icon);
        $model->icon = '';
        $model->save();
        return $this->redirect(['update', 'id' => $model->id]);
    }

    /**
     * Finds the StaticSeo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaticSeo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaticSeo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    function fillDopContent($info='')
    {
        $action = $this->action->id;
        $title = '';
        switch ($action)
        {
            case 'index':
                $title = 'СЕО статических страниц';
                break;
            case 'update':
                $title = 'Редактирование СЕО «'.$info->name.'»';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все страницы'],];
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}
