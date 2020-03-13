<?php


namespace app\modules\adm\controllers;

use app\commands\helpers;
use app\commands\ImagickHelper;
use app\controllers\SiteController;
use app\modules\adm\models\GaleriesSanatoriums;
use app\modules\adm\models\GPhotoSanatoriums;
use app\modules\adm\models\SanBlocks;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SansPrev;
use yii\web\Controller;
use Yii;
use app\modules\adm\models\Rooms;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class SanatoriumController extends Controller
{
    /**
     *
     * Метод генерации страницы всех санаториев в админке
     *
     *
     * @return string - отправка данных о санаториях для генерации GridView в представлении(sanatorium/index)
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SansPrev::find()->where(['id_city' => 0]),
        ]);

        return $this->render('index', [ "provider" => $dataProvider]);
    }

    /**
     *
     * Метод генерации страницы изменения данных о санатории в админке
     * Сохраняет новые данные полученные от пользователя(PJAX) в БД
     *
     *
     * @return string - отправка данных о санатории в представление(sanatorium/update)
     */
    public function actionUpdate($id = 0, $isDelete = false)
    {

        if(!$isDelete && (Yii::$app->request->isAjax)){
            $result = Yii::$app->request->post();

            if(!isset($result['SanBlocks']['id'])) {

                $this->createNewBlockOfSanatory($id, $result);

                unset($result);
            }
        }

        if(!$isDelete && (Yii::$app->request->isAjax)){
            $result = Yii::$app->request->post();
            if(isset($result['SanBlocks']['id'])) {
                $modelForBlocks = $this->savingDataOfblock($result, $id);
            }
        }

        $galleryesArray = $this->getGallery($id);

        $model = SansPrev::find()->where(['id' => $id])->one();
        $idSan = $model->id;

        if (!isset($modelForBlocks)){
            $modelForBlocks = SanBlocks::find()->where(['id_san' => $idSan])->orderBy('priority')->all();
        }

        if (($post = Yii::$app->request->post()) && !(Yii::$app->request->isAjax) && !($id == 0)) {
            foreach ($post['SansPrev'] as $key => $value) {
                if ($value != $model[$key]){
                    if (($key == 'file_name') && ($value == '')){
                        continue;
                    }
                    $model->$key = $value;
                }
            }
            if($file = $model->upload()){
                $model->file_name = $file;

                $resolutions = explode("x", Yii::$app->params['resolution_main_sanatorium_photo']);

                $post = [
                    'id' => $model->id,
                    'x1' => '0',
                    'y1' => '0',
                    'x2' => $resolutions[0],
                    'y2' => $resolutions[1],
                    'r' => Yii::$app->params['resolution_main_sanatorium_photo']
                ];

                ImagickHelper::Thumb($post, $model);
            }
            $model->save();
        }

        return $this->render('update', [
            'sanId' => $id,
            'model' => $model,
            'type' => Yii::$app->params['type_san_block'],
            'modelForBlock' => $modelForBlocks,
            'galleryes' => $galleryesArray
        ]);
    }

    public function actionNew_gallery($id, $name){

        $model = new GaleriesSanatoriums();

        $new_data = Yii::$app->request->post();

        if (!empty($new_data)){
            $model->id_resurs = $new_data['GaleriesSanatoriums']['id_resurs'];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['gallery_update', 'id' => $model->id, 'name' => $name]);
        }

        $this->fillDopContent($name);

        return $this->render('updateGallery', [
            'model' => $model,
            'idRes' => $id,
        ]);
    }

    public function actionGallery_update($id, $name){

        //vd(Yii::$app->request->post());

        $model = $this->findModel($id);//Gallery model
        $photos = $model->getGalleriesPhotos()->orderBy('priority')->all();//связь 1 ко многим и получение картинок из связи id_galleries

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(Model::loadMultiple($photos, Yii::$app->request->post()) && Model::validateMultiple($photos)){
                foreach ($photos as $photo) {$photo->save(false);}
            }

            $model->files_name = UploadedFile::getInstances($model, 'files_name');
            if($model->upload()){//загрузка новых файлов
                return $this->redirect(['gallery_update',
                    'id' => $model->id,
                    'name' => $name,
                ]);
            }
        }
        $skip = array('.', '..', 'original');
        $scan = scandir(GPhotoSanatoriums::DIR());

        foreach ($skip as $value){
            unset($scan[array_search($value, $scan)]);
        }
        natcasesort($scan);//сортировка массива по значению
        $resolutions = array_reverse($scan);

        $this->fillDopContent($name, $model);
        return $this->render('updateGallery', [
            'model' => $model,
            'photos'=>$photos,
            'resolutions'=>$resolutions,
            'idRes' => $id
        ]);
    }

    /**
     *
     * удаление болока санатория
     *
     * @param $id string/int - id блоко для удаления
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $result = Yii::$app->request->post();

        $model = SanBlocks::findOne($result['SanBlocks']['id']);
        $model->delete();

        $this->actionUpdate($id, true);
    }

    /**
     *
     * Страница комнат для их изменения
     *
     * @param $id int/string - id санатория
     *
     * @return string - отправка данных о санатории в представление(sanatorium/rooms)
     * @throws \yii\db\Exception
     */
    public function actionRooms($id, $sanatoriumName){

        $createdBlock = SanBlocks::find()->where(['type' => 5, 'id_san' => $id])->one();
        if (empty($createdBlock)){
            $this->createNewBlockOfSanatory($id);
        }
        elseif ($createdBlock->is_active == 0){
            $createdBlock->is_active = 1;
            $createdBlock->save();
        }

        $result = (Yii::$app->request->post()) ? Yii::$app->request->post() : false ;
        //vd($result);

        if (Yii::$app->request->isAjax){
            if (!isset($result['new-id'])){
                //создание новой строки в таблице
                $result = Yii::$app->request->post();
                $newData = new Rooms();
                $newData->id_san = $id;
                $newData->id_room_in_main_table = $result['id_room_in_main_table'];
                $newData->desc = $result['desc'];
                if ($result['gallery'] != '')
                    $newData->id_gallery = $result['gallery'];
                $newData->save();
            }
            else{
                $modelRooms = Rooms::find()->where(['id' => $result['new-id']])->one();
                $modelRooms->desc = $result['new-desc'];
                $modelRooms->id_gallery = $result['new-gallery'];
                $modelRooms->save();
            }
        }

        unset($result, $modelRooms, $newData);

        $SQL = 'SELECT `id_in_main_table` FROM `sanatoriums` WHERE `id` = :id';
        $id_in_main_table = Yii::$app->db->createCommand($SQL)->bindValues([':id' => $id] )->queryOne();
        $id_in_main_table = $id_in_main_table['id_in_main_table'];
        $roomsArray = SiteController::getRoomsDataForSanatorium($id_in_main_table);

        return $this->render('rooms', [
            'rooms' => $roomsArray[0],
            'modelRooms' => $roomsArray[1],
            'gallery' => $this->getGallery($id),
            'sanatoriumName' => $sanatoriumName,
            'sanatoryId' => $id
        ]);
    }

    /**
     * Deletes an existing Gallery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete_galery($idSan, $name, $idGallery)
    {
        $model = $this->findModel($idGallery);
        $photos = $model->getGalleriesPhotos()->all();
        foreach ($photos AS $photo){
            $this->actionDelete_photo($photo->id_photo, $idGallery, $name, $redirect = false);
        }
        $model->delete();

        return $this->redirect(['galleries_all', 'idSan' => $idSan, 'name' => $name]);
    }

    public function actionAjaxcreatethumb(){
        $this->enableCsrfValidation = false;

        //vd(Yii::$app->request->post());
        if (isset($_POST['is_main_sanatorium_photo'])){
            $model = SansPrev::findOne($_POST['id']);
        }else{
            $model = GPhotoSanatoriums::findOne($_POST['id']);
        }

        return json_encode(ImagickHelper::Thumb($_POST, $model));
    }

    public function actionReset_photo($id){
        $model = GPhotoSanatoriums::findOne($id);
        return json_encode(ImagickHelper::Reset($model));
    }

    public function actionDelete_photo($id, $id_res, $name, $redirect = true) //Доработать
    {
        if(empty($id))
            throw new NotFoundHttpException('Фотография не найдена.');
        $model = GPhotoSanatoriums::findOne($id);

        $image = ImagickHelper::Delete($model);
        if($image){
            if($redirect)
                $this->redirect(['gallery_update',
                    'id' => $id_res,
                    'name' => $name
                ]);
            else
                return true;
        }
    }

    public function actionGalleries_all($idSan, $name){
        $dataProvider = new ActiveDataProvider([
            'query' => GaleriesSanatoriums::find()->where(['id_resurs' => $idSan]),
        ]);
        return $this->render('allGalleries', [
            'dataProvider' => $dataProvider,
            'idSan' => $idSan,
            'sanName' => $name
        ]);
    }

    /**
     *
     * Получение всех зарегистрированых галерей
     *
     *
     * @return array - подготовленый массив галерей[ id-galleryes => name_galleryes ]
     */
    private function getGallery($idRes){
        $galleryes = GaleriesSanatoriums::find()->where(['id_resurs' => $idRes])->all();
        $galleryesArray = [];
        foreach ($galleryes as $value){
            $galleryesArray[$value->id] = $value->name;
        }

        return $galleryesArray;
    }

    /**
     * Finds the Gallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return mixed Gallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GaleriesSanatoriums::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Галерея не найдена.');
    }

    /**
     *
     * изменение болоков санатория
     *
     * @param $data array - получает массив из Yii::$app->request->post()
     *
     * @return $model - Элемкнт класса ActiveRecord или false в случае ошибки
     */
    private function savingDataOfblock($data, $idSan)
    {
        $model = SanBlocks::find()->where(['id_san' => $idSan])->orderBy('priority')->all();

        if (empty($data)){
            return false;
        }
        $data = $data['SanBlocks'];
        foreach ($model as $key=>$elem){
            if ($elem['id'] == $data['id']){
                $row = $key;
            }
        }

        $elem = $model[$row];

        if ($data['content'] != '')
            $elem->content = $data['content'];
        $elem->is_active = isset($data['is_active']) ? $data['is_active'] : '0';
        $elem->priority = isset($data['priority']) ? $data['priority'] : '99';

        $model[$row] = $elem;

        if($elem->save()){
            return $model;
        }
        return false;
    }

    /**
     *
     * Создаёт новый блок санатория
     *
     * @param $id int/string - id санатория
     * @param $result array - массиы данных для нового блока, если параметр не передан, создаст блок номеров.
     *
     */
    public function createNewBlockOfSanatory($id, $result = ['new-type' => '5', 'new_priority' => '99', 'value_is_active' => 0]){

        $newData = new SanBlocks();
        $newData->id_san = $id;
        $newData->type = $result['new-type'];
        $newData->priority = $result['new_priority'];
        $newData->is_active = isset($result['value_is_active']) ? $result['value_is_active'] : 0;
        $newData->save();
    }

    /**
     *
     * Создание бополнений
     *
     * @param $name string - Название элемента для которого создаётся библиотека.
     * @param $type int - тип создания
     * @param $info mixed - модель класска GalleriesSanatorium если существует
     *
     * @throws \yii\db\Exception
     */
    function fillDopContent($name, $info='')
    {
        $action = $this->action->id;
        $title = '';
        switch ($action)
        {
            case 'new_gallery':
                $title = 'Добавление галереи для санатория '.$name;
                break;
            case 'gallery_update':
                $title = 'Редактирование галлереи «'.$info->name.'» для санатория '.$name;
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}