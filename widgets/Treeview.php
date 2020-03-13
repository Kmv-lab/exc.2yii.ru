<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;

class Treeview extends Widget
{
    public $tree = [];
    public $id='';
    public $class='';

    public function run()
    {
        Yii::$app->view->registerJsFile('/admFiles/js/vakata-jstree-bc5187e/dist/jstree.min.js', ['depends'=>['yii\web\JqueryAsset']]);
        Yii::$app->view->registerCssFile('/admFiles/js/vakata-jstree-bc5187e/dist/themes/default/style.min.css');
        $html = '<div class="'.$this->class.'" id="'.$this->id.'"><ul>';
        foreach ($this->tree AS $el){
            $html .= $this->tree($el);
        }
        $html .= '</ul></div>';
        return $html;
    }

    function tree($el){
        $params = '';
        if(!isset($el['class']))
            $el['class'] = '';


        if($el['expanded'])
            $el['class'] .= ' jstree-open';
        if(isset($el['class']) && !empty($el['class']))
            $params .= ' class="'.$el['class'].'"';
        if(isset($el['id']) && !empty($el['id']))
            $params .= ' id="'.$el['id'].'"';
        $html = '<li'.$params.'>';
        $html .= $el['text'];
        if(isset($el['children']) && is_array($el['children'])){
            $html .= '<ul>';
            foreach ($el['children'] AS $el_children){
                $html .= $this->tree($el_children);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }
}
