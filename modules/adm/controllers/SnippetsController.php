<?php

namespace app\modules\adm\controllers;

use Yii;
use app\modules\adm\models\Snippet;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\commands\helpers;
use yii\helpers\Url;

/**
 * SnippetsController implements the CRUD actions for Snippet model.
 */
class SnippetsController extends Controller
{
    /**
     * Lists all Snippet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Snippet::find(),
        ]);

        $this->fillDopContent();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Snippet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Snippet();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id_snippet]);
        }

        $this->fillDopContent();
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Snippet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id_snippet]);
        }

        $this->fillDopContent($model);
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Snippet model.
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
     * Finds the Snippet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Snippet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Snippet::findOne($id)) !== null) {
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
                $title = 'Сниппеты';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['create']), 'name'=>'Добавить cниппет'],];
                break;
            case 'create':
                $title = 'Добавление cниппета';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все сниппеты'],];
                break;
            case 'update':
                $title = 'Редактирование cниппета «'.$info->snippet_name.'»';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все сниппеты'],
                    ['url' => Url::to(['create']), 'name'=>'Добавить cниппет'],];
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}
