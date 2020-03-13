<?php

namespace app\modules\adm\controllers;

use Yii;
use app\modules\adm\models\Testi;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\commands\helpers;
use yii\helpers\Url;

class TestiController extends Controller
{

    /**
     * Lists all Testi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Testi::find(),
        ]);

        $this->fillDopContent();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Testi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed */

    public function actionCreate()
    {
        $model = new Testi();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['update', 'id' => $model->id]);
        }

        $this->fillDopContent();
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Testi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $this->fillDopContent($model);
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Testi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Testi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Testi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Testi::findOne($id)) !== null) {
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
                $title = 'Отзывы';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['create']), 'name'=>'Добавить отзыв'],];
                break;
            case 'update':
                $title = 'Редактирование отзыва «'.$info->name.'»';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все отзывы'],
                    ['url' => Url::to(['create']), 'name'=>'Добавить отзыв'],
                ];
                break;
            case 'create':
                $title = 'Добавление отзыва';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все отзывы'],];
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}