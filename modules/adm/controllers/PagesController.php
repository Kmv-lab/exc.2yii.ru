<?php

namespace app\modules\adm\controllers;

use Yii;
use app\modules\adm\models\Page;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\commands\PagesHelper;
use app\commands\helpers;
use yii\helpers\Url;

/**
 * PagesController implements the CRUD actions for Page model.
 */
class PagesController extends Controller
{
    public $page_front_url ='';
    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $pagesTree      =   $this->createTreeFromPages(0);
        $this->fillDopContent();
        return $this->render('index',['pagesTree'=>$pagesTree] );
    }

    public function actionSort(){
        $i=0;
        foreach($_POST['sort'] AS $p){
            $columns['page_priority'] = $i+1;
            Yii::$app->db->createCommand()->update('pages',$columns,'id_page = :id')
                ->bindValue(':id', $p)->execute();
            $i++;
        }
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = 0)
    {
        $model = new Page();
        if (!empty($id))
            $model->id_parent_page = $id;
        $model->date_create = time();
        $model->date_update = time();
        if ($model->load(Yii::$app->request->post()) && $model->upload()) {
            return $this->redirect(['update', 'id' => $model->id_page]);
        }

        $this->fillDopContent();
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->date_update = time();

        if ($model->load(Yii::$app->request->post()) && $model->upload()) {
            return $this->redirect(['update', 'id' => $model->id_page]);
        }
        $this->page_front_url = PagesHelper::getUrlById($id);
        $this->fillDopContent($model);
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->delete_page($id);
        return $this->redirect(['index']);
    }

    function delete_page($id){
        $curr_page = Yii::$app->db->createCommand('SELECT id_page, no_del FROM pages WHERE id_page = :id')
            ->bindValue(':id', $id)->queryOne();
        if($curr_page['no_del'])
            return false;
        $SQL = 'SELECT id_page, no_del FROM pages WHERE id_parent_page = :id';
        $pages = Yii::$app->db->createCommand($SQL)->bindValue(':id', $id)->queryAll();
        foreach ($pages AS $page){
            if(!$page['no_del']){
                if(!$this->delete_page($page['id_page']))
                    return false;
            }else
                return false;
        }
        Yii::$app->db->createCommand()->delete('pages', 'id_page = :id')->bindValues([':id'=>$id])->execute();
        if(!empty($curr_page['file_name']))
            @unlink(Page::DIR().$curr_page['file_name']);
        return true;
    }

    public function actionDel_file($id){
        $model = $this->findModel($id);
        if(!empty($model->file_name)){
            @unlink($model->DIR().$model->file_name);
            $model->file_name = '';
            $model->save();
        }
        return $this->redirect(['update', 'id' => $model->id_page]);
    }
    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    public function createTreeFromPages($idParentPage = 0)
    {
        //vd(Yii::$app->params['pages']);

        $pages = PagesHelper::select(
            Yii::$app->params['pages'],
            array(),
            array(
                array('attr_name'   =>  'id_parent_page',   'operand'=> '=', 'value'=> $idParentPage ),
            ),
            array('attr_name'  => 'page_priority','sort_type'=>'ASC')
        );
        $resultArr = array();
        foreach ($pages AS $page)
        {
            $resultArr[] = array(
                'text'          => $this->renderPagesMenuItem($page),
                'expanded'      => true,
                'id'            => 'sort_'.$page['id_page'],
                //'hasChildren'   => true,
                'children'      => $this->createTreeFromPages($page['id_page']));
        }
        return $resultArr;
    }
    public function renderPagesMenuItem($page)
    {
        $item = $this->renderPartial('_menuItem',['page'=>$page],true);
        return $item;
    }


    function fillDopContent($info='')
    {
        $action = $this->action->id;
        $title = '';
        switch ($action)
        {
            case 'index':
                $title = 'Страницы';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['create']), 'name'=>'Добавить страницу'],];
                break;
            case 'create':
                $title = 'Добавление страницы';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все страницы'],];
                break;
            case 'update':
                $title = 'Редактирование страницы «'.$info->page_name.'»';
                $this->view->params['dopMenu'] = [
                    ['url' => Url::to(['index']), 'name'=>'Все страницы'],
                    ['url' => Url::to(['create', 'id'=>$info->id_parent_page]), 'name'=>'Добавить страницу'],];
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}
