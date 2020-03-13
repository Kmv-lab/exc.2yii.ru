<?php
namespace app\commands;

use Yii;
use yii\helpers\Url;

class PagesHelper
{
    /**
     * Функция возвращает отсортированный массив необходимых значений
     * @param array $pages Массив страниц для поиска
     * @param array $select массив атрибутов для выборки / пока не работает
     * @param array $where Массив массивов условий вида:  array('attr_name'   =>  'id_parent_page',   'operand'=> '=', 'value'=> '0' ),
     * @param array $order Массив вида:  $order['sort_type'] $order['attr_name']
     */
    public static function select($pages, $select = [],$where = [], $order = '')
    {
        //vd($pages);
        $resultPages = array();
        foreach ($pages AS $page)
        {
            $pageSatisfies = true;
            foreach ($where AS $condition)
            {
                //vd($condition);
                switch ($condition['operand'])
                {
                    case '=':
                        if ( $page[$condition['attr_name']] == $condition['value'] )
                        {
                            $pageSatisfies = $pageSatisfies && true;
                        }
                        else
                        {
                            $pageSatisfies = $pageSatisfies && false;
                        }
                        break;
                }
                //vd($pageSatisfies);

            }
            //echo '-';echo $pageSatisfies;
            if ($pageSatisfies){
                if(empty($page['page_breadcrumbs_name']) || $page['page_breadcrumbs_name'] == '')
                    $page['page_breadcrumbs_name'] = $page['page_menu_name'];
                $resultPages[$page['id_page']] = $page;

            }

        }

        //vd($resultPages);

        if (!empty($order))
        {
            $resultPages = PagesHelper::order($resultPages,$order);
        }

        return $resultPages;
    }

    /**
     * array('attr_name'   =>  'page_priority', 'sort_type'=>'ASC')
     */
    public static function order($pages,$order)
    {
        //vd($pages);
        if($order['sort_type'] == 'ASC')
            uasort($pages, self::sortASC($order['attr_name']));
        else if($order['sort_type'] == 'DESC')
            uasort($pages, self::sortDESC($order['attr_name']));
        return $pages;
    }

    static function sortASC($attr_name) {
        return static function ($a, $b) use ($attr_name){
            if ($a[$attr_name] == $b[$attr_name])
                return 0;
            return ($a[$attr_name] < $b[$attr_name]) ? -1 : 1;
        };
    }

    static function sortDESC($attr_name) {
        return static function($a, $b) use ($attr_name) {
            if ($a[$attr_name] == $b[$attr_name])
                return 0;
            return ($a[$attr_name] > $b[$attr_name]) ? -1 : 1;
        };
    }

    /**
     * Получаем массив страниц, соответствующий структуре URL
     * @param array $urlArr Массив алиасов
     * @return mixed массив страниц
     */
    public static function getPagesInUrl($urlArr)
    {
        $parentPageId   = 0;
        $okURL = [];
        $statusFind     = false;
        $pages = Yii::$app->params['pages'];
        foreach ($urlArr AS $aliasInUrl)
        {
            // сверяем элемент массива URL со значением алиасов в массиве страниц
            foreach ($pages AS $page)
            {
                // если совпадает и алиас и родитель
                if ($page['page_alias'] == $aliasInUrl && $page['id_parent_page'] == $parentPageId)
                {
                    //добавляем в массив $this->okURL все данные о текущей странице
                    $okURL[]        =   $page;
                    $parentPageId   =   $page['id_page'];
                    $statusFind     =   true;
                    break;
                }
            }
            // если страница(совпадение) не найдена то 404, иначе обуляем статус "найдено" для следующего уровня
            if (!$statusFind)
                    return false;
            else
                $statusFind = false;

        }
        return $okURL;
    }

    /**
     * @param array $url если истина, значит последний элемент тоже будет ссылкой
     * @param bool $dontStop если истина, значит последний элемент тоже будет ссылкой
     * @return array возвращаем массив элементов для breadcrumbs breadcrumbs[] = Array ('url'=>$path, 'name'=>$name, 'title'=>$page['page_link_title']) ;
     */
    public static function generateBreadcrumbs($url,$dontStop=false){
        // дабы не выводить один неактивный элемент, проверяем размерность массива $url
        //if (count($url)<=1) return;
        //теперь создаём ссылку на каждую хлебную крошку
        $pageLevel          =   count($url);
        $i=1;
        $path = '';
        $breadcrumbs = [];
        foreach ($url As $page)
        {
            $name = empty($page['page_breadcrumbs_name'])  ?     $page['page_menu_name']    :   $page['page_breadcrumbs_name'];
            //$path .= '/'.$page['page_alias'].'/';
            $path = self::generateUrlByPage($page);
            //если не текущий раздел, тогда передаём название и ссылку , иначе просто название
            if ($i == $pageLevel && !$dontStop)
            {
                if (!empty($name))
                    $breadcrumbs[] = ['label'=>$name] ;
            }
            else
            {
                $breadcrumbs[] = ['url'=>$path, 'label'=>$name, 'title'=>$page['page_link_title']] ;
            }
            $i++;
        }
        return $breadcrumbs;
    }

    /**
     * @param array $page
     * @return string Получаем путь до страницы , входной параметр - массив с полями страницы
     */
    public static function generateUrlByPage($page){

        $pages = Yii::$app->params['pages'];
        $id_parent = $page['id_parent_page'];
        $URL = $page['page_alias'].'/';
        while ($id_parent != 0)
        {
            foreach($pages AS $P)
            {
                if ($P['id_page'] == $page['id_parent_page'])
                {
                    $URL = $P['page_alias'].'/'.$URL;
                    $page = $P;
                    $id_parent = $P['id_parent_page'];
                }
            }
        }
        $URL = '/'.$URL;
        return $URL;
    }

    /**
     * Получаем путь к странице по её ID
     */
    public static function getUrlById($id)
    {
        //$page = Yii::app()->db->createCommand('Select * From pages Where id_page = :id')->queryRow(true,array(':id'=>$id));
        $page = PagesHelper::select(Yii::$app->params['pages'],[],[['attr_name' => 'id_page', 'operand'=> '=', 'value'=> $id ]] );
        if(empty($page))
            return '#';
        $p = current($page);
        $id_parent = $p['id_parent_page'];
        $path = '/'.$p['page_alias'].'/';
        //d::dump($page,false);
        while ($id_parent != 0)
        {
            //$page = Yii::app()->db->createCommand('Select * From pages Where id_page = :id')->queryRow(true,array(':id'=>$id_parent));
            $page = PagesHelper::select(Yii::$app->params['pages'],[],[['attr_name' => 'id_page', 'operand'=> '=', 'value'=> $id_parent ]] );
            $p = current($page);
            $id_parent = $p['id_parent_page'];
            $path = '/'.$p['page_alias'].$path;
        }
        return $path;
    }
}