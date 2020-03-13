<?php

namespace app\modules\adm;

use Yii;
/**
 * adm module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\adm\controllers';
    public $layout = 'adm';
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        ini_set('apc.cache_by_default', '0');//отключение кеширвоания апс
        ini_set('opcache.enable', 0);
        parent::init();
        // custom initialization code goes here
    }
    public function beforeAction($action){
        if(Yii::$app->user->isGuest && $action->id != 'login'){
            Yii::$app->response->redirect(['adm/users/login'], 303);
            return false;
        }else{
           // session_start();


            $_SESSION['ckfinder_key']   =   '44a75943874f8a0487534e0a32c1c873';
            return true;
        }
    }
}
