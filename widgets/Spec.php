<?php
namespace app\widgets;

use yii\base\Widget;
use Yii;

class Spec extends Widget
{

    public function run()
    {
        if(isset($_GET['page']))
            $page = (int)$_GET['page'];
        else
            $page = 1;
        Yii::$app->view->title .= ' - Страница '.$page;
        $request = $this->spec_request(false,$page);
        $spec = Yii::$app->db->createCommand($request['sql'])->bindValues($request['params'])->queryAll();
        $request = $this->spec_request();
        $count = Yii::$app->db->createCommand($request['sql'])->bindValues($request['params'])->queryScalar();
        return $this->render('spec', ['spec'=>$spec, 'page'=>$page, 'count'=>$count]);
    }

    function spec_request($count = true, $page = 1){//page == 0 выберет все без пагинации
        $params = [];
        $limit = '';
        if(!$count && $page != 0){
            $count_item = (int)Yii::$app->params['count_spec_items'];
            $limit = ' LIMIT '.($page-1)*$count_item.','.$count_item;
        }
        $where = ' WHERE is_active = 1';
        $order = ' ORDER BY date_publication DESC';
        $select = $count ? 'count(id)' :'*';
        $SQL = 'SELECT '.$select.'
                FROM spec'.$where.$order.$limit;
        return ['sql'=>$SQL, 'params'=>$params];
    }
}