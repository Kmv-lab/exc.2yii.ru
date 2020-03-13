<?php

namespace app\modules\adm\controllers;

use app\commands\ImagickHelper;
use Yii;
use app\modules\adm\models\News;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\commands\helpers;
use yii\helpers\Url;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex($type)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->where(['type'=>(int)$type]),
        ]);
        $this->fillDopContent($type);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed */

    public function actionCreate($type)
    {
        $model = new News();

        if ($model->load(Yii::$app->request->post()) && $model->upload($type)) {
            return $this->redirect(['update', 'id' => $model->id_news]);
        }

        $this->fillDopContent($type);
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->upload($model->type)) {
            return $this->redirect(['update', 'id' => $model->id_news]);
        }

        $skip = array('.', '..', 'original');
        $scan = scandir($model->DIR());
        $newScan=$scan;


        //start
        /*foreach ($skip as $value){
            unset($newScan[array_search($value, $newScan)]);
        }
        natcasesort($newScan);//сортировка массива по значению
        $newScan = array_reverse($newScan);*/
        //vd($newScan, false);
        //return

        echo "<br>";

        //Странная ментальная эквелибристика для того, чтобы разрешения с равными ширинами не пропадали.
        //!!!НЕ ПЫТАТЬСЯ ПОНЯТЬ!!! прими как данность. Это "чтобы разрешения с равными ширинами не пропадали."
        //start
        foreach($scan as $key=>$resolution) {
            if(!in_array($resolution, $skip)){
                $key_for_sort = explode('x',$resolution);
                $key_for_sort = $key_for_sort[0].$key;
                //vd($key_for_sort, false);
                $resolutions[$key_for_sort] = $resolution;
            }
        }
        krsort($resolutions);
        //vd($resolutions);
        $tempArr = [];
        foreach($resolutions AS $resolution){
            $tempArr[] = $resolution;
        }
        $resolutions = $tempArr;
        //vd($resolutions, false);
        //return



        $this->fillDopContent($model);
        return $this->render('update', [
            'model' => $model, 'resolutions'=>$resolutions
        ]);
    }

    /**
     * Deletes an existing News model.
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
        $model = News::findOne($_POST['id']);
        return json_encode(ImagickHelper::Thumb($_POST, $model));
    }

    public function actionReset_photo($id){
        $model = News::findOne($id);
        return json_encode(ImagickHelper::Reset($model));
    }

    public function actionDelete_photo($id)
    {
        if(empty($id))
            throw new NotFoundHttpException('Фотография не найдена.');
        $model = News::findOne($id);
        $model->delete_photo();
        $model->save();
        $this->redirect(['update', 'id' => $model->id_news]);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    function my_mb_ucfirst($str) {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }
    function fillDopContent($info='')
    {
        $action = $this->action->id;
        $title = '';
        $array_name = [ 1=>['новости', 'новость', 'новость'],['записи', 'запись', 'запись'], ['статьи', 'статья', 'статью']];

        switch ($action)
        {
            case 'index':
                $title = $this->my_mb_ucfirst($array_name[$info][0]);
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['create', 'type'=>$info]), 'name'=>'Добавить '.$array_name[$info][2]],
                ];
                break;
            case 'update':
                $title = 'Редактирование '.$array_name[$info->type][0].' «'.$info->name.'»';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index', 'type'=>$info->type]), 'name'=>'Все '.$array_name[$info->type][0]],
                    ['url' => Url::to(['create', 'type'=>$info->type]), 'name'=>'Добавить '.$array_name[$info->type][2]],
               //     ['url' => Url::to(['create']), 'name'=>'Добавить новость/акцию'],
                ];
                break;
            case 'create':
                $title = 'Создание '.$array_name[$info][0];
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index', 'type'=>$info]), 'name'=>'Все '.$array_name[$info][0]],];
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}
