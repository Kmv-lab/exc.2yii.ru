<?php

namespace app\widgets;

use yii\base\Widget;
use Yii;
use yii\helpers\Url;

class Pagination extends Widget
{

    public $page = 1;
    public $count = 0;
    public $pageSize = 20;
    public $action = '';

    public $max_show_pages = 5;

    public function init()
    {
        if(Yii::$app->controller->action->id == 'page')
            $this->action = '/'.Yii::$app->request->pathInfo;
        else
            $this->action = Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
        parent::init();
       // ob_start();
      //  ob_implicit_flush(false);
    }

    public function run()
    {

        //vd(Yii::$app->controller);
        $canonical_url = Yii::$app->request->hostInfo.'/'.Yii::$app->request->pathInfo;
      //  if($this->page != 1){
        //  Yii::$app->view->registerLinkTag(['rel' => 'canonical', 'href' => $canonical_url]);
      //  }
        if($this->page > 1){
            $prev_url = Yii::$app->request->hostInfo.URL::to([$this->action]);
            if($this->page > 2)
                $prev_url .= ($this->page - 1).'/';
            Yii::$app->view->registerLinkTag(['rel' => 'prev', 'href' => $prev_url]);
        }

        if($this->page < ceil($this->count/$this->pageSize)){
            $next_url = $canonical_url;
            $next_url .= ($this->page + 1).'/';
            Yii::$app->view->registerLinkTag(['rel' => 'next', 'href' => $next_url]);
        }
        if($this->count/$this->pageSize > 1)
            echo $this->render_ul();
    }

    function render_ul(){
        $count_page = ceil($this->count/$this->pageSize);
        $max_p = $this->max_show_pages;
        $start_page = 1;
        if($count_page > $max_p){
            if($this->page <= round($max_p/2)){
                $start_page = 1;
            }elseif($count_page-$this->page < round($max_p/2)){
                $start_page = $count_page-$max_p+1;
            }else{
                $start_page = $this->page-round($max_p/2)+1;
            }
        }else{
            $max_p = $count_page;
        }
        $html = '<div class="pager">';
        if($this->page > 1){
            $url = $this->page == 2 ? URL::to([$this->action]) : URL::to([$this->action, 'page'=>$this->page-1]);
            $html .=     '<a href="'.$url.'"><</a>';
        }
        for($x=$start_page; $x<$start_page+$max_p;$x++){
            if($x==$this->page)
                $html .= '<span>'.$x.'</span>';
            else{
                if($x == 1)
                    $url = URL::to([$this->action]);
                else
                    $url = URL::to([$this->action, 'page'=>$x]);

                $html .= '<a title="Страница '.$x.'" href="'.$url.'">'.$x.'</a>';
            }
        }

        if($this->page < $count_page)
            $html .=     '<a href="'.URL::to([$this->action, 'page'=>$this->page+1]).'">></a>';
        $html .=  '</div>';

        return $html;
    }
}
