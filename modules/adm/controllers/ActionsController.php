<?php

namespace app\modules\adm\controllers;

use app\commands\ImagickHelper;
use Yii;
use app\modules\adm\models\Actions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\commands\helpers;
use yii\helpers\Url;


class ActionsController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Actions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Actions::find(),
        ]);
        $this->fillDopContent();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Actions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed */

    public function actionCreate()
    {
        $model = new Actions();

        if ($model->load(Yii::$app->request->post()) && $model->upload()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $this->fillDopContent();
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Actions model.
     * If update is successful, the browser will be redirected to the 'view' page.
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

        $skip = array('.', '..', 'original');
        $scan = scandir($model->DIR());
        foreach($scan as $key=>$resolution) {
            if(!in_array($resolution, $skip)){
                $key_for_sort = explode('x',$resolution);
                $key_for_sort = $key_for_sort[0].$key;
                $resolutions[$key_for_sort] = $resolution;
            }
        }
        krsort($resolutions);
        $tempArr = [];
        foreach($resolutions AS $resolution){
            $tempArr[] = $resolution;
        }
        $resolutions = $tempArr;
        $this->fillDopContent($model);
        return $this->render('update', [
            'model' => $model, 'resolutions'=>$resolutions
        ]);
    }

    /**
     * Deletes an existing Actions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete_photo();
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionAjaxcreatethumb(){
        $this->enableCsrfValidation = false;
        $model = Actions::findOne($_POST['id']);
        return json_encode(ImagickHelper::Thumb($_POST, $model));
    }

    public function actionReset_photo($id){
        $model = Actions::findOne($id);
        return json_encode(ImagickHelper::Reset($model));
    }

    public function actionDelete_photo($id)
    {
        if(empty($id))
            throw new NotFoundHttpException('Фотография не найдена.');
        $model = Actions::findOne($id);
        $model->delete_photo();
        $model->save();
        $this->redirect(['update', 'id' => $model->id]);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Actions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Actions::findOne($id)) !== null) {
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
                $title = 'Акции';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['create']), 'name'=>'Добавить акцию',]
                ];
                break;
            case 'update':
                $title = 'Редактирование акции «'.$info->name.'»';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все акции'],
                    ['url' => Url::to(['create']), 'name'=>'Добавить акцию']
                ];
                break;
            case 'create':
                $title = 'Создание акции';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index', 'type'=>$info]), 'name'=>'Все акции']];
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}