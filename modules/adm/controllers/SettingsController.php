<?php
namespace app\modules\adm\controllers;


use app\modules\adm\models\SettingForm;
use Yii;
use yii\web\Controller;
use yii\base\Model;
use app\commands\helpers;

class SettingsController  extends Controller
{

    public function actionIndex(){
        $SQL = 'SELECT * FROM settings_sys ORDER BY id';
        $settings = Yii::$app->db->createCommand($SQL)->queryAll();
        $models = [];
        foreach ($settings AS $setting){
            $models[$setting['id']] = new SettingForm();
            $models[$setting['id']]->set_sys_value = $setting['set_sys_value'];
        }
        if ((Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models))) {
            foreach ($models AS $key=>$model){
                Yii::$app->db->createCommand()
                    ->update('settings_sys',['set_sys_value'=>$model->set_sys_value], 'id = :id')
                    ->bindValue(':id', $key)->execute();
            }

        }
        helpers::createSeo('', 'Настройки', 'Настройки');
        return $this->render('index', ['models'=>$models, 'settings'=>$settings]);
    }

}