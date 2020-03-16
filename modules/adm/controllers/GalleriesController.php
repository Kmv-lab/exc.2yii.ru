<?php

namespace app\modules\adm\controllers;

use app\commands\ImagickHelper;
use Yii;
use app\modules\adm\models\Gallery;
use app\modules\adm\models\GalleryPhoto;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\commands\helpers;
use yii\web\UploadedFile;
use yii\base\Model;

/**
 * GalleriesController implements the CRUD actions for Gallery model.
 */
class GalleriesController extends Controller
{
    /**
     * Lists all Gallery models.
     * @return mixed
     */
    public function actionIndex()//вывод всех подходящих галерей
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Gallery::find(),
        ]);
        $this->fillDopContent();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Gallery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()//создание новой галереи
    {
        $model = new Gallery();



        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //vd($model);
            return $this->redirect(['update', 'id' => $model->id_gallery]);
        }

        $this->fillDopContent();

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Gallery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);//Gallery model
        $photos = $model->getGalleriesPhotos()->orderBy('priority')->all();//связь 1 ко многим и получение картинок из связи id_galleries

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(Model::loadMultiple($photos, Yii::$app->request->post()) && Model::validateMultiple($photos)){
                foreach ($photos as $photo) {$photo->save(false);}
            }
            $model->files_name = UploadedFile::getInstances($model, 'files_name');
            if($model->upload()){//загрузка новых файлов
                return $this->redirect(['update', 'id' => $model->id_gallery]);
            }
        }
        $skip = array('.', '..', 'original');
        $scan = scandir(GalleryPhoto::DIR());

        //Где-то я это видел, топ тема ин зе ворлд. 10 ментальных эквилибристов из 10
        /*foreach($scan as $key=>$resolution) {
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
        vd($resolutions);*/
        //Конец странностей.

        //Новый код, который понятней и минималистичней. :)
        foreach ($skip as $value){
            unset($scan[array_search($value, $scan)]);
        }
        natcasesort($scan);//сортировка массива по значению
        $resolutions = array_reverse($scan);


        $this->fillDopContent($model);
        return $this->render('update', [
            'model' => $model, 'photos'=>$photos, 'resolutions'=>$resolutions
        ]);
    }

    /**
     * Deletes an existing Gallery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $photos = $model->getGalleriesPhotos()->all();
        foreach ($photos AS $photo){
            $this->actionDelete_photo($photo->id_photo, $redirect = false);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionAjaxcreatethumb(){

        //vd("rereree");

        $this->enableCsrfValidation = false;
        $model = GalleryPhoto::findOne($_POST['id']);

        //vd($model);

        return json_encode(ImagickHelper::Thumb($_POST, $model));
    }

    public function actionReset_photo($id){
        $model = GalleryPhoto::findOne($id);
        return json_encode(ImagickHelper::Reset($model));
    }

    public function actionDelete_photo($id, $redirect = true) //Доработать
    {
        if(empty($id))
            throw new NotFoundHttpException('Фотография не найдена.');
        $model = GalleryPhoto::findOne($id);

        $image = ImagickHelper::Delete($model);
        if($image){
            if($redirect)
                $this->redirect(['update', 'id' => $model->id_gallery]);
            else
                return true;
        }
    }

    /**
     * Finds the Gallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Gallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gallery::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Галерея не найдена.');
    }


    function fillDopContent($info='')
    {
        $action = $this->action->id;
        //vd($this->action->id);
        $title = '';
        switch ($action)
        {
            case 'index':
                $title = 'Галереи';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['create']), 'name'=>'Добавить галерею'],];
                break;
            case 'create':
                $title = 'Добавление галереи';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все галереи'],];
                break;
            case 'update':
                $title = 'Редактирование страницы «'.$info->name.'»';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все галереи'],
                    ['url' => Url::to(['create']), 'name'=>'Добавить галерею'],];
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}
