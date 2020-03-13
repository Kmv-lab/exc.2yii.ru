<?php
namespace app\modules\adm\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\commands\helpers;

class ResolutionController extends Controller
{
    public function actionIndex()
    {
        $path = Yii::$app->params['path'];
        $parent     = Yii::$app->params['resolutions_parent_folders'];
        $folders    = Yii::$app->params['resolutions_folders'];
        $skip = array('.', '..', 'original');
        $resolutionsArr = [];
        foreach($folders AS $key=>$folder){
            $path_folder = $path.'/'.$parent.'/'.$folder.'/';
            $resolutions = scandir($path_folder);
            $resolutionsArr[$key] = [
                'text'          => '<div>'.$folder.' - <div class="btn btn-success btn-xs add_form" data-name="'.$folder.'"><span class="glyphicon glyphicon-plus"></div></div>',
                'expanded'      => true,
                'id'            => 'folder_'.$folder ];
            foreach($resolutions as $resolution) {
                if(!in_array($resolution, $skip)){
                    $resolutionsArr[$key]['children'][] = [
                        'text'          => '<div>'.$resolution.' - <a href="'.Url::to(['resolution/delete','resolution'=>$resolution, 'folder'=>$folder]) .'" class="deleteItem btn btn-default btn-xs" >Удалить</a></div>',
                        'expanded'      => true,
                        'id'            => $folder.'_'.$resolution];
                }
            }
        }
        $this->fillDopContent();
        return $this->render('index', array('resolutionsArr'=>$resolutionsArr));
    }

    public function actionAdd($folder, $width, $height){
        $path = Yii::$app->params['path'];
        $parent     = Yii::$app->params['resolutions_parent_folders'];
        mkdir($path.'/'.$parent.'/'.$folder.'/'.(int)$width.'x'.(int)$height, 0755);
        return 'ok';
    }

    public function actionDelete($resolution, $folder){
        $path = Yii::$app->params['path'];
        $parent     = Yii::$app->params['resolutions_parent_folders'];
        $this->removeDirectory($path.'/'.$parent.'/'.$folder.'/'.$resolution);
        $this->redirect(Url::to(['resolution/index']));
    }

    function removeDirectory($dir) {
        if ($objs = glob($dir."/*")) {
            foreach($objs as $obj) {
                is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    function fillDopContent($info='')
    {
        $action = $this->action->id;
        $title = '';
        switch ($action)
        {
            case 'index':
                $title = 'Разрешения изображений';
                break;
        }
        helpers::createSeo('', $title, $title);
    }
}