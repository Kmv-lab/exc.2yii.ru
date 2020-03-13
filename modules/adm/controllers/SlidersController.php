<?php

namespace app\modules\adm\controllers;

use Yii;
use app\modules\adm\models\Slider;
use app\modules\adm\models\SliderPhotos;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\commands\helpers;
use yii\web\UploadedFile;
use yii\base\Model;
use app\commands\ImagickHelper;

/**
 * SlidersController implements the CRUD actions for Slider model.
 */
class SlidersController extends Controller
{
    /**
     * Lists all Slider models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Slider::find(),
        ]);

        $this->fillDopContent();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Slider model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $photos = $model->getSliderPhotos()->orderBy('priority')->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(Model::loadMultiple($photos, Yii::$app->request->post()) && Model::validateMultiple($photos)){
                foreach ($photos as $photo) {$photo->save(false);}
            }
            $model->files_name = UploadedFile::getInstances($model, 'files_name');
            if($model->upload()){
                return $this->redirect(['update', 'id' => $model->id_slider]);
            }
        }
        $skip = array('.', '..', 'original');
        $scan = scandir(SliderPhotos::DIR());
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
            'model' => $model, 'photos'=>$photos, 'resolutions'=>$resolutions
        ]);
    }


    public function actionAjaxcreatethumb(){
        $this->enableCsrfValidation = false;
        $model = SliderPhotos::findOne($_POST['id']);
        return json_encode(ImagickHelper::Thumb($_POST, $model));
    }

    public function actionReset_photo($id){
        $model = SliderPhotos::findOne($id);
        return json_encode(ImagickHelper::Reset($model));
    }

    public function actionDelete_photo($id, $redirect = true) //Доработать
    {
        if(empty($id))
            throw new NotFoundHttpException('Фотография не найдена.');
        $model = SliderPhotos::findOne($id);

        $image = ImagickHelper::Delete($model);
        if($image){
            if($redirect)
                $this->redirect(['update', 'id' => $model->id_slider]);
            else
                return true;
        }
    }


    /**
     * Finds the Slider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Slider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Slider::findOne($id)) !== null) {
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
                $title = 'Слайдеры';
                break;
            case 'update':
                $title = 'Редактирование слайдера «'.$info->name.'»';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все слайдеры'],];
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}
