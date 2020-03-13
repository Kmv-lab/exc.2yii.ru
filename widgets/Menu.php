<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use app\commands\PagesHelper;

class Menu extends Widget
{
    public function run()
    {
        $urlArr = explode('/',Yii::$app->request->pathInfo);
        unset($urlArr[count($urlArr)-1]);
        $okURL  = PagesHelper::getPagesInUrl($urlArr);
        return $this->render('menu', ['pages'=>$this->getAllChildPages(0), 'okURL'=>$okURL]);
    }
    function getAllChildPages($id=0){

        $return = [];
        $pages  =   PagesHelper::select(Yii::$app->params['pages'],
            [],
            [
                ['attr_name'   =>  'id_parent_page',   'operand'=> '=', 'value'=> $id ],
                ['attr_name'   =>  'show_in_menu',     'operand'=> '=', 'value'=> '1'],
                ['attr_name'   =>  'is_active',        'operand'=> '=', 'value'=> '1' ],
            ],
            ['attr_name'   =>  'page_priority', 'sort_type'=>'ASC']
        );
        foreach ($pages as $key=>$page){
            $return[$key] = ['label'=> $page['page_menu_name'], 'alias'=>$page['page_alias'],
                'page_link_title'=>$page['page_link_title'], 'id_page'=>$page['id_page'],];
            $return[$key]['items'] = self::getAllChildPages($key);
        }
        return $return;
    }

    function generateLi(){

    }
}