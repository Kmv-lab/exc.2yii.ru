<?php

namespace app\modules\adm\controllers;

use Yii;
use yii\web\Controller;
use app\modules\adm\models\UserForm;
use app\modules\adm\models\LoginForm;
use app\modules\adm\models\User;
use yii\web\HttpException;
use yii\web\Response;
use app\commands\helpers;

use yii\helpers\Url;
use yii\data\SqlDataProvider;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller{

    public function actionLogin(){
        if (!Yii::$app->user->isGuest) {
            //vd($this);
            return $this->redirect(['/adm/']);
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //vd($this);
            return $this->redirect(['/adm/']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionIndex()
    {
        $where = '';
        if(!Yii::$app->user->identity->getAccess('system')){
            $where .= ' WHERE id <> 1 AND id <> 7';
            return $this->render('index', ['stop' => true]);
        }

        $totalCount = Yii::$app->db->createCommand('SELECT COUNT(*) FROM users'.$where)->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => 'SELECT * FROM users'.$where,
           // 'params' => [':publish' => 1],
            'totalCount' => $totalCount,
            //'sort' =>false, to remove the table header sorting
            'sort' => [
                'attributes' => [
                    'name' => [
                        'asc' => ['name' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Имя',
                    ],
                    'id_user' => [
                        'asc' => ['id_user' => SORT_ASC],
                        'desc' => ['id_user' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'ID',
                    ],
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
            'key'  => 'id',
        ]);
        $this->fillDopContent();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddAdmin() {
        $model = User::find()->where(['username' => 'admin'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'admin';
            $user->email = 'admin@admin.ru';
            $user->setPassword('admin');
            $user->generateAuthKey();
            if ($user->save()) {
                echo 'good';
            }
        }
    }

    public function actionCreate()
    {
        $model  =   new UserForm(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = $model->create();
            return $this->redirect(['update', 'id' => $user['id']]);
        }
        $this->fillDopContent();
        return $this->render('edit', ['model'=>$model]);
    }

    public function actionUpdate($id)
    {vd(234234);
        $SQL = 'SELECT * FROM users WHERE id = :id';
        $user = Yii::$app->db->createCommand($SQL)->bindValue(':id', $id)->queryOne();
        if(empty($user))
            throw new HttpException(404 ,'Пользователь не найден');
        $model  =   new UserForm(['scenario' => 'update']);
        $model->attributes = $user;
        $model->password = '';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->update();
        }else{
            vd($model->getErrors());
        }
        $this->fillDopContent();

        return $this->render('edit', ['model' => $model,]);
    }

    /**
     * Deletes an existing Report model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        Yii::$app->db->createCommand()->delete('users', 'id = :id')->bindValue(':id', $id)->execute();
        return $this->redirect(['index']);
    }

    function fillDopContent($info='')
    {
        $action = $this->action->id;
        $title = '';
        //d::dump($action);
        switch ($action)
        {
            case 'index':
                $title = 'Пользователи';
                $this->view->params['dopMenu'] = [
                                    ['url' => Url::to(['create']), 'name'=>'Добавить'],];
            break;
            case 'update':
                $title = 'Редактирование';
                $this->view->params['dopMenu'] = [
                                    ['url' => Url::to(['index']), 'name'=>'Пользователи'],
                                    ['url' => Url::to(['create']), 'name'=>'Добавить'],];
            break;
            case 'create':
                $title = 'Добавление';
                $this->view->params['dopMenu'] = [
                                    ['url' => Url::to(['index']), 'name'=>'Пользователи'],];
            break;
        }
        helpers::createSeo('', $title, $title);
    }
}
?>