<?php

namespace app\controllers;

use app\helpers\PagesHelper;
use yii\web\HttpException;
use app\helpers\d;
use Yii;

class PagesController extends MainController
{
    public $layout = 'page';

    public function actionVer(){
        $this->layout = 'page_ver';
        return  $this->render('index');
    }
    public function actionIndex()
    {
vd(Yii::$app->params['pages']);
        // Удаляем все отключённые страницы из $this->pages
        $this->pages = PagesHelper::select($this->pages,array(),array(array('attr_name'   =>  'is_active',   'operand'=> '=', 'value'=> '1')));//, '', true , array('attr_name'=>'page_priority', 'sort_type'=>'ASC')
        // Получаем массив страниц соответствующий текущему url, отправляем URL и все-все страницы
        $urlArr = $this->urlArr;
        $this->okURL = $this->getPagesInUrl($urlArr,$this->pages);
        $this->currentPage = $this->okURL[count($this->okURL)-1];
        $currentPage = $this->currentPage;

        $this->breadcrumbs = $this->generateBreadcrumbs($this->okURL);

        //============ Обработка вызовов виджетов в тексте
        $currentPage['page_content'] = d::checkForWidgets($currentPage['page_content']);
//        d::dump($currentPage);
        $SQL = 'SELECT * FROM pages_ext WHERE id_page = :id';
        $page_ext = Yii::$app->db->createCommand($SQL)->bindValue(':id', $currentPage['id_page'])->queryOne();
        $page_ext['page_content'] = d::checkForWidgets($page_ext['page_content']);
        return $this->render('index',['page' => $page_ext]);

    }

    //откуда $row
    public function actionAjaxEvents($id_page, $rows, $count=0){

        if($row)
            $limit = (int)Yii::$app->params['count_events_pl']+1;
        else
            $limit = (int)Yii::$app->params['count_events_row']+1;
        $events = $this->eventsSQL((int)$id_page,(int)$limit, (int)$count);
        $limit--;
        if(count($events) > $limit){
            $return['load'] = 1;
            unset($events[$limit]);
        }else{
            $return['load'] = 0;
        }
        $return['html'] = $this->renderPartial('events_items',array('events'=>$events,'plitka'=>!(int)$row, 'count'=>$count), true);
        $return['count'] = count($events) + (int)$count;
        echo json_decode($return);
    }

    public function actionAjaxSearchIndicator(){
        $post = $_POST['Search'];
        $post['url_page'] = $_SERVER['HTTP_REFERER'];
        Yii::$app->session['search'] = $post;
        $post['row'] = 1;
        $post['count'] = 0;
        $limit = (int)Yii::$app->params['count_search_indicator'] < 1 ? 10 : (int)Yii::$app->params['count_search_indicator'];
        $DataAll = $this->searchSQL($post,101);
        $return['html'] = $this->renderPartial('search_indicator',array('DataAll'=>$DataAll, 'limit'=>$limit));
        //  $return['count'] = $countD;
        // $return['load'] = $load;
        echo json_encode($return);
    }

    public function actionAjaxSearch($ajax=1){
        $post = $_POST['Search'];
        $post['url_page'] = $_SERVER['HTTP_REFERER'];
        Yii::$app->session['search'] = $post;
        if($post['rows'])
            $limit = (int)Yii::$app->params['count_rows'] < 1 ? 10 : (int)Yii::$app->params['count_rows']+1;
        else
            $limit = (int)Yii::$app->params['count_plitka'] < 1 ? 10 : (int)Yii::$app->params['count_plitka']+1;
        //$limit = 100+1;
        //d::dump($post);
        $DataAll = $this->searchSQL($post,$limit);
        //$return['log'] = $DataAll;
        $limit--;
        $countD = count($DataAll);
        //d::dump($DataAll);
        if($countD > $limit){
            $countD--;
            unset($DataAll[$limit]);
            $load = 1;
        }else
            $load = 0;
        if($post['rows']){
            $guides_temp = Yii::$app->db->createCommand('SELECT * FROM guides')->queryAll();
            foreach($guides_temp AS $guide){
                $guides[$guide['id_guide']] = $guide;
            }
            $return['html'] = $this->renderPartial('rows',array('DataAll'=>$DataAll, 'tags'=>$this->tagsSQL(), 'guides'=>$guides), true);
        }else{
            if($post['spisok'] == 0){
                $full_search['count'] = $countD;
            }else
                $full_search = 0;
            $return['html'] = $this->renderPartial('plitka',array('DataAll'=>$DataAll, 'tags'=>$this->tagsSQL(), 'full_search'=>$full_search), true);
        }

        //$return['html'] = $this->renderPartial('plitka',array('DataAll'=>$DataAll, 'tags'=>$this->tagsSQL(), 'id_page'=>$post['id_page']), true);
        $return['count'] = $countD;
        $return['load'] = $load;
        echo json_encode($return);
    }

    public function actionRaspisanie($type=0, $start = 0, $end = 0, $plitka = 1){
        $this->layout = 'page';
        $this->pages = PagesHelper::select($this->pages,array(),array(array('attr_name'   =>  'is_active',   'operand'=> '=', 'value'=> '1')));//, '', true , array('attr_name'=>'page_priority', 'sort_type'=>'ASC')
        $pages = $this->pages;
        $page = $pages[$this->id_page_except];
        $this->createSEO($page,$page['page_name'],$page['page_name'],'','');
        $this->breadcrumbs = $this->generateBreadcrumbs($this->okURL);
        $SQL = 'SELECT * FROM pages_ext WHERE id_page = :id';
        $page_ext = Yii::$app->db->createCommand($SQL)->queryOne(true,array(':id'=>$page['id_page']));
        $this->currentPage  = array_merge($page,$page_ext);
        $page = $this->currentPage;
        $page['page_type'] = 1;
        if(empty($_POST['return'])){
            Yii::$app->session['search'] = null;
            $forview = $this->getForViewList($page, 0, $type, $start,$end, $plitka);
        }elseif(!empty($_POST['Search'])){
            $post = $_POST['Search'];
            $post['url_page'] = $_SERVER['REDIRECT_URL'];
            Yii::$app->session['search'] = $post;
            $forview = $this->getForViewSearch($page);
        }else{
            $forview = $this->getForViewSearch($page);
        }
        return $this->render($forview['view'],$forview['array']);
    }

    public function actionAjaxSelectRoute($id_route){
        if(empty($id_route))
            throwException( new HttpException(500));
        $SQL = 'SELECT excursion_routes.*,
                        guides.name AS guide_name,
                        guides.family,
                        guides.patronymic,
                        guides.alias AS guide_alias
                FROM excursion_routes
                LEFT JOIN guides
                ON excursion_routes.id_guide = guides.id_guide
                WHERE id_route = :id_route';
        $route = Yii::$app->db->createCommand($SQL)->queryOne(true, array(':id_route'=>(int)$id_route));
        $return['guide'] = !empty($route['patronymic']) ? '<div class="gid"><div>ГИД:</div> <a>'.$route['family'].' '.$route['guide_name'].' '.$route['patronymic'].'</a></div>' : 'no';
        $return['text'] = $route['text'];
        if(!empty($route['extra_pay'])){
            $route['extra_pay'] = explode(PHP_EOL, $route['extra_pay']);
            $return['extra_pay'] = '<h3>Оплачивается отдельно</h3>
                <ul>';
            foreach($route['extra_pay'] AS $li){
                $return['extra_pay'] .= '<li>'.$li.'</li>';
            }
            $return['extra_pay'] .= '</ul>';
        }else
            $return['extra_pay'] = '';
        echo json_encode($return);
    }

    public function actionAjaxChangeAddress($id_excur, $id_address){
        if(empty($id_excur) || empty($id_address))
            throwException( new HttpException(500));
        $SQL = 'SELECT * FROM excursions WHERE id_excursion = :id_excursion';
        $excursion = Yii::$app->db->createCommand($SQL)->queryOne(true, array(':id_excursion'=>$id_excur));
        $SQL = 'SELECT excursion_routes.*, excursion_photos.file_name, excursion_photos.alt
                FROM excursion_routes
                LEFT JOIN excursion_photos
                ON excursion_photos.id_photo = IFNULL(excursion_routes.main_photo,0)
                WHERE excursion_routes.id_excursion = :id_excursion AND excursion_routes.start_address = :id_address
                ORDER BY priority';
        $routes = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_excursion'=>$excursion['id_excursion'], ':id_address'=>$id_address));
        foreach($routes AS $route){
            $select_routes[$route['id_route']] = $route;
        }
        $price = $this->priceSelectRoute($excursion,$select_routes);
        if(!empty($price)){
            $return['html'] = Yii::$app->controller->renderPartial('excur_price',
                array('categories'=>$this->categoriesExcSQL($excursion),
                    'price'=>$price,
                    'excursion'=>$excursion,
                    'select_routes'=>$select_routes,
                    'select_route'=>$routes[0],
                    'ajax'=>1));
            $return['js'] = Yii::$app->controller->renderPartial('exc_date_js',
                array('price'=>$price,));
        }else
            $return['html'] = Yii::$app->controller->renderPartial('excur_price_order',array('excursion'=>$excursion,'select_routes'=>$select_routes, 'select_route'=>$routes[0]));
        if(!empty($routes[0]['id_guide'])){
            $SQL = 'SELECT * FROM guides WHERE id_guide = :id_guide';
            $guide = Yii::$app->db->createCommand($SQL)->queryOne(true, array(':id_guide'=>$routes[0]['id_guide']));
        }
        $return['guide'] = !empty($guide) ? '<div class="gid"><div>ГИД:</div> <a href="#">'.$guide['family'].' '.$guide['name'].' '.$guide['patronymic'].'</a></div>' : 'no';
        $return['text'] = $routes[0]['text'];
        if(!empty($routes[0]['extra_pay'])){
            $routes[0]['extra_pay'] = explode(PHP_EOL, $routes[0]['extra_pay']);
            $return['extra_pay'] = '<h3>Оплачивается отдельно</h3>
                <ul>';
            foreach($routes[0]['extra_pay'] AS $li){
                $return['extra_pay'] .= '<li>'.$li.'</li>';
            }
            $return['extra_pay'] .= '</ul>';
        }else
            $return['extra_pay'] = '';
        $return['id_route'] = $routes[0]['id_route'];
        echo json_encode($return);
    }

    public function actionAjaxChangeDataPriceExc($id_route, $data, $type){
        if(empty($data) || empty($id_route) || empty($type))
            throwException( new HttpException(500));
        $data = explode('.',$data);
        $data = mktime(0,0,0,$data[1],$data[0],$data[2]);
        if($type == 1){
            $day_week = date('w', $data);
            if($day_week == 0)
                $day_week = 7;
            $SQL = 'SELECT *
                    FROM (SELECT id_group,`day`,`time` FROM exc_route_times WHERE id_route = :id_route AND `day` = :day)day_group
                    JOIN
                    (SELECT * FROM exc_route_price WHERE id_route = :id_route AND `start` <= :data AND `end` >= :data)price
                    ON day_group.id_group = price.id_group';
            $price = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_route'=>$id_route,
                ':data'=>$data,
                ':day'=>$day_week));
        }else{
            $SQL = 'SELECT *
                    FROM (SELECT id_group,`day`,`time` FROM exc_route_times WHERE id_route = :id_route AND `day` = :data)day_group
                    JOIN
                    (SELECT * FROM exc_route_price WHERE id_route = :id_route AND `start` <= :data AND `end` >= :data)price
                    ON day_group.id_group = price.id_group';
            $price = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_route'=>$id_route,
                ':data'=>$data));
        }

        // d::dump($price);
        $return['data'] = d::russian_date($data).' в ';
        $return['data'] = str_replace('&nbsp;', ' ', $return['data']);
        $return['reserve_url'] = $this->createUrl('site/reserve', array('type'=>1,
            'data'=>$data,
            'id_route'=>$id_route));
        foreach($price AS $p){
            $return['price'][$p['id_str']][$p['id_cat_price']] = $p['price'];
            $times[$p['time']] = $p['time'];
        }
        foreach($times AS $t){
            $return['data'] .= $t.', ';
        }
        $return['data'] = substr($return['data'],0,-2);
        foreach($return['price'] AS $r){
            $return['price'] = $r;
            break;
        }
        echo json_encode($return);
    }

    public function actionAjaxPriceTour($data, $id_tour, $count = 0){
        if(empty($data) || empty($id_tour))
            throwException( new HttpException(500));
        $time = explode('.',$data);
        $time = mktime(0,0,0,$time[1],$time[0],$time[2]);
        $weekday = date('w', $time);
        $weekday = $weekday == 0 ? 7 : $weekday;
        $arr_pdo = array(':id_tour'=>$id_tour,
            ':time'=>$time,
            ':weekday'=>$weekday,);
        $SQL = 'SELECT * FROM tours
                LEFT JOIN tour_times
                ON tour_times.id_tour = tours.id_tour
                WHERE tours.id_tour = :id_tour
                    AND tours.is_active = 1';
        if($count != 0){
            $SQL .= '   AND tour_times.count = :count';
            $arr_pdo[':count'] = $count;
        }
        $SQL .= '   AND (tour_times.start = :time OR tour_times.start = :weekday)';
        $tour_times = Yii::$app->db->createCommand($SQL)->queryAll(true, $arr_pdo);
        //d::dump($tour_time);
        if($tour_times[0]['start'] < 8)
            $tour_times[0]['start'] = $time;
        $hotels = $this->priceTourSQL($tour_times, $tour_times[0]);
        return $this->renderPartial('tour_price',array('hotels'=>$hotels,
            'categories'=>$this->categoriesTourSQL($tour_times[0]),
            'id'=>$id_tour,
            'data'=>$time,
            'count'=>$tour_times[0]['count_days']==0 ? $count : $tour_times[0]['count_days']));
    }

    public function actionAjaxList($id_page, $type, $count = 0, $start = 0, $end=0, $rows = 0, $main=0)
    {
        $time_current = time();
        if($type == 2 && $time_current > $start)
            $start = $time_current;
        if($main)
            $limit = (int)Yii::$app->params['count_block_main'] < 1 ? 6 : (int)Yii::$app->params['count_block_main']+1;
        elseif($row = 0)
            $limit = (int)Yii::$app->params['count_plitka'] < 1 ? 10 : (int)Yii::$app->params['count_plitka']+1;
        else
            $limit = (int)Yii::$app->params['count_rows'] < 1 ? 10 : (int)Yii::$app->params['count_rows']+1;
        $DataAll = $this->plitkaSQL($id_page, $type, $limit, (int)$count, $start, $end, $rows);
        //d::dump($DataAll);
        $limit--;
        $countD = count($DataAll);
        //d::dump($DataAll);
        if($countD > $limit){
            $countD--;
            unset($DataAll[$limit]);
            $btn = 0;
        }else
            $btn = 1;
        if($rows){
            $guides_temp = Yii::$app->db->createCommand('SELECT * FROM guides')->queryAll();
            foreach($guides_temp AS $guide){
                $guides[$guide['id_guide']] = $guide;
            }
            //d::dump($DataAll);
            $html = Yii::$app->controller->renderPartial('rows',array('DataAll'=>$DataAll, 'tags'=>$this->tagsSQL(), 'type'=>$type, 'id_page'=>$id_page, 'guides'=>$guides));
        }else
            $html = Yii::$app->controller->renderPartial('plitka',array('DataAll'=>$DataAll, 'tags'=>$this->tagsSQL(), 'type'=>$type, 'id_page'=>$id_page));
        echo json_encode(array('html'=>$html,
            'button'=>$btn,
            'count'=>$countD+$count));
    }

    function getAllChilds($page)
    {
        if ( !empty($page) )
        {
            $tempArr = array();
            foreach ($this->pages AS $P)
            {
                if ($P['id_parent'] == $page['id_page'])
                    $tempArr[]  = $P;
            }
            return $tempArr;
        }
        else
            throwException( new HttpException(500,'Функцией Childs не был получен входной параметр!'));
    }

    function getAllBrothers($page)
    {
        if ( !empty($page) )
        {
            $tempArr = array();
            foreach ($this->pages AS $P)
            {
                if ($P['id_parent'] == $page['id_parent'])
                    $tempArr[]  = $P;
            }
            return $tempArr;
        }
        else
            throwException( new HttpException(500,'Функцией Brothers не был получен входной параметр!'));
    }

    function getForViewSearch($page){
        $post = Yii::$app->session['search'];
        $post['count'] = 0;
        if($post['rows'])
            $limit = (int)Yii::$app->params['count_rows'] < 1 ? 10 : (int)Yii::$app->params['count_rows']+1;
        else
            $limit = (int)Yii::$app->params['count_plitka'] < 1 ? 10 : (int)Yii::$app->params['count_plitka']+1;
        $DataAll = $this->searchSQL($post,$limit);
        $limit--;
        $countD = count($DataAll);
        if($countD > $limit){
            $countD--;
            unset($DataAll[$limit]);
            $load = 1;
        }else
            $load = 0;
        $post['count'] = $countD;
        $full_search['count'] = $countD;
        $full_search['load'] = $load;
        return array('view'=>'search', 'array'=> array('page'=>$page,
            'DataAll'=>$DataAll,
            'tags'=>$this->tagsSQL(),
            'full_search'=>$full_search,
            'post'=>$post));
    }

    function getForViewTour($tour){
        if(!empty(Yii::$app->session['search'])){
            if($_SERVER['HTTP_REFERER'] != Yii::$app->session['search']['url_page']){
                Yii::$app->session['search'] = null;
            }
        }
        /** Запрос фоток*/
        $SQL = 'SELECT * FROM tour_photos WHERE id_tour = :id_tour ORDER BY priority';
        $photos = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_tour'=>$tour['id_tour']));

        /** Запрос описаний всех дней*/
        $SQL = 'SELECT * FROM tour_days WHERE id_tour = :id_tour ORDER BY number_day';
        $days = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_tour'=>$tour['id_tour']));

        /** Запрос отзывов */
        $SQL = 'SELECT * FROM tour_testimonials WHERE id_tour = :id_tour AND is_active = 1 ORDER BY data DESC';
        $testimonials = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_tour'=>$tour['id_tour']));





        /** Запрос всех дат начала тура*/

        $timestamp_day_now = date('d.m.Y');
        $timestamp_day_now = explode('.',$timestamp_day_now);
        $timestamp_day_now = mktime(0,0,0,$timestamp_day_now[1],$timestamp_day_now[0],$timestamp_day_now[2]);
        if($tour['type_tour'] == 2){
            $SQL = 'SELECT * FROM tour_times WHERE id_tour = :id_tour AND start >= :time ORDER BY start, count';
            $times = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_tour'=>$tour['id_tour'], ':time'=>$timestamp_day_now));
        }else if($tour['type_tour'] == 1){
            $sort = $this->ArraySortDay(date('w'));
            $SQL = 'SELECT * FROM tour_times WHERE id_tour = :id_tour ORDER BY FIELD(`start`, '.$sort.'), count';
            $times_t = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_tour'=>$tour['id_tour']));
            foreach($times_t AS $time){
                $arr_day[$time['start']] = $time['start'];
                $tour_start = $tour['data_start'] >= $timestamp_day_now ? $tour['data_start'] : $timestamp_day_now;
                $w_start = date('w',$tour_start) == 0 ? 7 : date('w',$tour_start);
                $count_day = $time['start']-$w_start;
                $count_day = $count_day >= 0 ? $count_day : $count_day+7;
                $start = $tour_start + ($count_day * 86400);
                for($i = $start; $i <= $tour['data_end']+86400; $i += 604800){
                    $times[] = array('start' => $i, 'count'=> $time['count'], 'id_group'=>$time['id_group']);
                    //echo 'i='.date('n-j-Y',$i).', $tour[data_end]='.date('n-j-Y',$tour['data_end']).PHP_EOL;
                }
            }
            //d::dump($times);
            //d::dump($times);
        }
        /** Обработка данных сессии для выбора даты*/
        $post_search = Yii::$app->session['search'];
        if(!empty($post_search['start']) && preg_match('/^[0-3][0-9]\.[0-1][0-9]\.20[1-9][0-9]$/', $post_search['start'])){
            $session_date['start'] = explode('.',$post_search['start']);
            $session_date['start'] = mktime(0,0,0,$session_date['start'][1],$session_date['start'][0],$session_date['start'][2]);
        }
        if(!empty($post_search['end']) && preg_match('/^[0-3][0-9]\.[0-1][0-9]\.20[1-9][0-9]$/', $post_search['end'])){
            $session_date['end'] = explode('.',$post_search['end']);
            $session_date['end'] = mktime(0,0,0,$session_date['end'][1],$session_date['end'][0],$session_date['end'][2]);
        }
        if(!empty($post_search['count_days']) && preg_match('/^[1-7]\-[1-7]$/', $post_search['count_days'])){
            $session_date['count'] = explode('-',$post_search['count_days']);
        }
        if(!empty($session_date)){
            foreach($times AS $time){
                $array_days = array();
                $this_date = true;
                if($session_date['start'] > 0){
                    if($time['start'] < $session_date['start']){
                        $this_date = false;
                    }
                }
                if($session_date['end'] > 0){
                    if($time['start'] > $session_date['end']){
                        $this_date = false;
                    }
                }
                if(!empty($session_date['count'])){
                    if($time['count'] < $session_date['count'][0] || $time['count'] > $session_date['count'][1]){
                        $this_date = false;
                    }
                }
                if($this_date){
                    $selet_time = $time;
                    break;
                }
            }
        }
        if(empty($selet_time))
            $selet_time = $times[0];

        if(!empty($times)){
            foreach($times AS $time){
                if($time['start'] == $selet_time['start'] && $time['count'] == $selet_time['count']){
                    $times_for_price[] = $time;
                }
            }
            $hotels = $this->priceTourSQL($times_for_price,$tour);
        }
        /** Запросы всех сервисов по хранящимся в строке ИД*/
        $tour['services'] = $this->servicesSQL($tour['services']);
        $tour['conditions'] = $this->conditionsSQL($tour['conditions']);
        $tour['payments'] = $this->paymentsSQL($tour['payments']);
        $tour['faq'] = $this->faqSQL($tour['faq']);
        //tour_origin
        return array('view'=>'tour', 'array'=>array('tour'=>$tour,
            'photos'=>$photos,
            'days'=>$days,
            'testimonials'=>$testimonials,
            'times'=>$times,
            'hotels'=>$hotels,
            'categories'=>$this->categoriesTourSQL($tour),
            'arr_day'=>$arr_day,
            'selet_time'=>$selet_time));
    }

    function getForViewGuides($page){
        $SQL = 'SELECT * FROM guides ORDER BY';
        return array('view'=>'guides', 'array'=>array('page'=>$page));
    }

    function getForViewEvents($page){
        $limit = (int)Yii::$app->params['count_events_pl']+1;
        $events = $this->eventsSQL($page['id_page'],$limit);
        $limit--;
        if(count($events) > $limit){
            $load = 1;
            unset($events[$limit]);
        }else{
            $load = 0;
        }
        return array('view'=>'events', 'array'=>array('page'=>$page, 'events'=>$events,'load'=>$load));
    }

    function getForViewEvent($event){
        $event['content'] = d::checkForWidgets($event['content']);
        return array('view'=>'event', 'array'=>array('event'=>$event));
    }

    function getForViewExc($excursion){
        if(!empty(Yii::$app->session['search'])){
            if($_SERVER['HTTP_REFERER'] != Yii::$app->session['search']['url_page']){
                Yii::$app->session['search'] = null;
            }
        }
        /** Запрос фоток*/
        $SQL = 'SELECT * FROM excursion_photos WHERE id_excursion = :id_excursion AND for_main = 1 ORDER BY priority';
        $photos = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_excursion'=>$excursion['id_excursion']));
        //d::dump($excursion);
        /** Запрос отзывов */
        $SQL = 'SELECT * FROM exc_testimonials WHERE id_excursion = :id_excursion AND is_active = 1 ORDER BY data DESC';
        $testimonials = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_excursion'=>$excursion['id_excursion']));

        /** Запрос всех связанных экскурсий по секциям(основная, школьная, вип и т.д.)*/
        Yii::import('application.modules.adm.models.FormExcursion');//требуется подключение БД прим.
        $model = new FormExcursion();
        $arr_name_section = $model->TypeSectionNames();
        $forOnJoin = '';
        foreach($arr_name_section AS $name){
            $forOnJoin .= ' excursions_t.id_excursion = sections.'.$name.' OR';
        }
        $forOnJoin = substr($forOnJoin,0,-3);
        $SQL = 'SELECT * FROM
                	(SELECT * FROM excursion_sections WHERE '.$arr_name_section[$excursion['id_section']].' = :id_excursion)sections
                JOIN
                	(SELECT alias, id_excursion, id_section, main_section FROM excursions WHERE is_active = 1)excursions_t
                ON '.$forOnJoin.'
                ORDER BY excursions_t.id_section';
        $exc_sections = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_excursion'=>$excursion['id_excursion']));

        /** Начало для маршрутов и их цен*/
        $SQL = 'SELECT excursion_routes.*, addresses.id_address, addresses.name AS name_address, excursion_photos.file_name, excursion_photos.alt
                FROM excursion_routes
                JOIN addresses
                ON addresses.id_address = excursion_routes.start_address
                LEFT JOIN excursion_photos
                ON excursion_photos.id_photo = IFNULL(excursion_routes.main_photo,0)
                WHERE excursion_routes.id_excursion = :id_excursion
                ORDER BY priority';
        $routes = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_excursion'=>$excursion['id_excursion']));
        $select_address = $routes[0]['id_address'];
        $select_route = $routes[0];
        foreach($routes AS $route){
            $addresses[$route['id_address']] = $route['name_address'];
            if($route['start_address'] == $select_address){
                $select_routes[$route['id_route']] = $route;
            }
        }
        //d::dump($select_routes);
        //$SQL = ''
        /** Конец для маршрутов и их цен*/

        /** Запрос гида первого маршрута если он есть*/
        if(!empty($routes[0]['id_guide'])){
            $SQL = 'SELECT * FROM guides WHERE id_guide = :id_guide';
            $guide = Yii::$app->db->createCommand($SQL)->queryRow(true, array(':id_guide'=>$routes[0]['id_guide']));
            //d::dump($guide);
        }

        /** Запросы всех сервисов по хранящимся в строке ИД*/
        $excursion['services'] = $this->servicesSQL($excursion['services']);
        $excursion['conditions'] = $this->conditionsSQL($excursion['conditions']);
        $excursion['payments'] = $this->paymentsSQL($excursion['payments']);
        $excursion['faq'] = $this->faqSQL($excursion['faq']);

        return array('view'=>'excursion',
            'array'=>array('excursion'=>$excursion,
                'photos'=>$photos,
                'testimonials'=>$testimonials,
                'exc_sections'=>$exc_sections,
                'guide'=>$guide,
                'select_routes'=>$select_routes,
                'select_route'=>$select_route,
                'addresses'=>$addresses,
                'categories'=>$this->categoriesExcSQL($excursion),
                'price'=>$this->priceSelectRoute($excursion,$select_routes)));
    }

    function getForViewCarousels($page){
        $current_week_day = date('w');
        $sort = $this->ArraySortDay($current_week_day);
        $current_week_day = $current_week_day == 0 ? 7 : $current_week_day;
        $SQL = 'SELECT * FROM page_carousels WHERE id_page = :id_page ORDER BY priority';
        $carousels = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_page'=>$page['id_page']));
        $limit = (int)Yii::$app->params['carousel_count_obj'] > 0 ? (int)Yii::$app->params['carousel_count_obj'] : 5;
        foreach($carousels AS $carousel){
            if(!empty($carousel['tags'])){
                $carousel['tags'] = explode(',', $carousel['tags']);
                $where_tag = '';
                foreach($carousel['tags'] AS $tag){
                    $where_tag .= ' id_tag = '.(int)$tag.' OR';
                }
                $where_tag = substr($where_tag,0,-3);
                $tags_temp_tours = ' (SELECT * FROM tour_tags WHERE'.$where_tag.')tags_temp ';
                $tags_temp_exc = ' (SELECT * FROM excursion_tags WHERE'.$where_tag.')tags_temp ';
                $tags_on_tours = 'tours_time.id_tour = tags_temp.id_tour';
                $tags_on_exc = 'tags_temp.id_excursion = excursions_time.id_excursion';
            }else{
                $tags_temp_tours = ' (SELECT 1 AS id_tour) AS tags_temp ';
                $tags_temp_exc = ' (SELECT 1  AS id_excursion) AS tags_temp ';
                $tags_on_tours = 'tags_temp.id_tour = 1';
                $tags_on_exc = 'tags_temp.id_excursion = 1';
            }
            if(!empty($carousel['pages'])){
                $carousel['pages'] = explode(',', $carousel['pages']);
                $where_page_exc = '';
                $where_page_tour = '';
                foreach($carousel['pages'] AS $id_page){
                    if($this->pages[$id_page]['page_type'] == 1){
                        $where_page_tour .= 'tours.main_section = '.(int)$id_page.' OR tours.add_section LIKE "%,'.(int)$id_page.',%" OR ';
                        $where_page_exc .= 'excursions.main_section = '.(int)$id_page.' OR excursions.add_section LIKE "%,'.(int)$id_page.',%" OR ';
                    }
                }
                if(!empty($where_page_tour)){
                    $where_page_tour = substr($where_page_tour,0,-4);
                    $where_page_exc = substr($where_page_exc,0,-4);
                    $where_page_tour = '('.$where_page_tour.') AND ';
                    $where_page_exc = '('.$where_page_exc.') AND ';
                }
            }
            // Закончила страницах нужно МНОГО страниц генерация
            $end = $carousel['data_end'];
            $SQL = '
    (SELECT tour_filter.id_tour AS id, tour_filter.name, tour_filter.alias, tour_filter.priority, tour_filter.type_tour AS type,
				(IF(tour_filter.sort IS NULL ,9, tour_filter.sort))sort,
	          tour_filter.`start`,
              tour_filter.start_price,
	                	tour_filter.data_start, tour_filter.data_end, tour_filter.length, tour_filter.main_section,
	                	(photos.file_name)ph_file_name, price_min.price
	FROM
		(SELECT tours_time.* FROM
			'.$tags_temp_tours.'
		JOIN
			(SELECT tours_t.*,
				(IF(tour_times_w.`start` < 8, GROUP_CONCAT(DISTINCT tour_times_w.`start` ORDER BY FIELD(tour_times_w.`start`, '.$sort.')), GROUP_CONCAT(DISTINCT tour_times_w.`start` ORDER BY tour_times_w.`start`)))`start`,
				IF(tour_times_w.`start` < 8,
						:start +
						IF(SUBSTRING_INDEX(GROUP_CONCAT(tour_times_w.`start` ORDER BY FIELD(tour_times_w.`start`, '.$sort.')), ",", 1) - '.$current_week_day.' < 0,
							SUBSTRING_INDEX(GROUP_CONCAT(tour_times_w.`start` ORDER BY FIELD(tour_times_w.`start`, '.$sort.')), ",", 1) + 7 - '.$current_week_day.',
							SUBSTRING_INDEX(GROUP_CONCAT(tour_times_w.`start` ORDER BY FIELD(tour_times_w.`start`, '.$sort.')), ",", 1) - '.$current_week_day.'
						) * 86400,
						SUBSTRING_INDEX(GROUP_CONCAT(tour_times_w.`start` ORDER BY tour_times_w.`start`), ",", 1)
				) AS sort
			FROM
				(SELECT id_tour, name, alias, priority, data_end, data_start, type_tour, length, main_section, start_price
		      FROM tours
		      WHERE '.$where_page_tour.' tours.is_active = 1 AND data_end > :start';
            if(!empty($end)){
                $SQL .= " AND data_start <= ".(int)$end;
            }
            $SQL .= ')tours_t
		   LEFT JOIN
		   	(SELECT * FROM tour_times WHERE `start` < 8 OR `start` >= :start)tour_times_w
		   ON tour_times_w.id_tour = tours_t.id_tour
		   GROUP BY tours_t.id_tour)tours_time
		ON '.$tags_on_tours;
            if(!empty($end)){
                $SQL .= " AND (tours_time.sort <= ".(int)$end." OR tours_time.type_tour = 3) ";
            }
            $SQL .= '
		GROUP BY tours_time.id_tour
		ORDER BY tours_time.priority
		LIMIT '.$limit.')tour_filter
	LEFT JOIN
		(SELECT tour_photos.*
		FROM tour_photos
		JOIN (SELECT id_tour, MIN(priority) AS priority FROM tour_photos GROUP BY id_tour)photo_min
		ON photo_min.id_tour = tour_photos.id_tour AND photo_min.priority = tour_photos.priority
		GROUP BY tour_photos.id_tour)photos
	ON tour_filter.id_tour = photos.id_tour
	LEFT JOIN
		(SELECT * FROM
			(SELECT tour_time_price.id_tour, price.price
			FROM tour_time_price
			RIGHT JOIN
				(SELECT tour_price.id_time_price, tour_price.price
				FROM tour_price
				RIGHT JOIN
					(SELECT id_category FROM price_category_tour WHERE `default` = 1)category
				ON category.id_category = tour_price.id_cat_price)price
			ON price.id_time_price = tour_time_price.id_time_price
			WHERE tour_time_price.`end` > :start ORDER BY tour_time_price.`start`, price.price)price_main_order
		GROUP BY id_tour)price_min
	ON tour_filter.id_tour = price_min.id_tour)
UNION ALL
	(SELECT excursion_filter.id_excursion AS id, excursion_filter.name, excursion_filter.alias, excursion_filter.priority, excursion_filter.type_excursion AS type,
							(IF(excursion_filter.sort IS NULL ,9, excursion_filter.sort))sort,
                     excursion_filter.`start`,
                     excursion_filter.start_price,
                		null, null, excursion_filter.`length`, excursion_filter.main_section,
                		(photos.file_name)ph_file_name, excursion_filter.price
	FROM
		(SELECT excursions_time.* FROM
            '.$tags_temp_exc.'
		JOIN
			(SELECT excursions_t.*,
					IF(times.`day` < 8,
						GROUP_CONCAT(times.`day`,"&",times.times ORDER BY FIELD(times.`day`, '.$sort.')),
						GROUP_CONCAT(times.`day`,"&",times.times ORDER BY times.`day`)
					) AS `start`,
					IF(times.`day` < 8,
						:start +
						IF(SUBSTRING_INDEX(GROUP_CONCAT(times.`day` ORDER BY FIELD(times.`day`, '.$sort.')), ",", 1) - '.$current_week_day.' < 0,
							SUBSTRING_INDEX(GROUP_CONCAT(times.`day` ORDER BY FIELD(times.`day`, '.$sort.')), ",", 1) + 7 - '.$current_week_day.',
							SUBSTRING_INDEX(GROUP_CONCAT(times.`day` ORDER BY FIELD(times.`day`, '.$sort.')), ",", 1) - '.$current_week_day.'
						) * 86400,
						SUBSTRING_INDEX(GROUP_CONCAT(times.`day` ORDER BY times.`day`), ",", 1)
					) AS sort,
                    times.price
		   FROM
			   (SELECT  id_excursion, name, alias, priority, type_excursion, main_section, start_price, `length`
			   FROM excursions
			   WHERE '.$where_page_exc.' excursions.is_active = 1)excursions_t
		   LEFT JOIN
                (SELECT exc_r_t.id_excursion, exc_r_t.`day`,
                                                (GROUP_CONCAT(`time` ORDER BY `time` SEPARATOR  "&"))`times`, price_str.price
                FROM (SELECT * FROM exc_route_times WHERE `day` >= :start OR `day`< 8)exc_r_t
                LEFT JOIN
                   (SELECT id_excursion, id_route, id_group, `start`, `end`,start_w,end_w, GROUP_CONCAT(temp_price.price ORDER BY temp_price.priority)price
                    FROM
                        (SELECT id_excursion, price, priority, id_route, id_group, `start`, `end`,
                            IF(DATE_FORMAT(FROM_UNIXTIME(`start`),"%w") = 0, 7, DATE_FORMAT(FROM_UNIXTIME(`start`),"%w")) AS start_w,
                            IF(DATE_FORMAT(FROM_UNIXTIME(`end`),"%w") = 0, 7, DATE_FORMAT(FROM_UNIXTIME(`end`),"%w")) AS end_w
                         FROM
                            (SELECT id_excursion, price, id_cat_price, id_route, id_group, `start`, `end`
                             FROM exc_route_price
                             WHERE ';
            if(!empty($end)){
                $SQL .= '`start` <= '.(int)$end.' AND ';
            }
            $SQL .= '`end` >= :start)price
                         JOIN
                             (SELECT * FROM price_category_excursion WHERE `default` = 1)category
                         ON price.id_cat_price = category.id_category)temp_price
                    GROUP BY id_excursion, id_route, id_group)price_str
                 ON exc_r_t.id_route = price_str.id_route
                    AND price_str.id_group = exc_r_t.id_group AND
                    ((exc_r_t.`day` < 8';
            if(!empty($end)){
                $SQL .= ' AND (IF(start_w > exc_r_t.`day`,7-(start_w-exc_r_t.`day`),exc_r_t.`day` - start_w))*86400 + `start` <= '.(int)$end;
            }
            $SQL .= ' AND `end` - (IF(end_w < exc_r_t.`day`,7-(exc_r_t.`day`-end_w),end_w-exc_r_t.`day`))*86400 >= :start)
                    OR exc_r_t.`day` > 7)
                GROUP BY exc_r_t.id_excursion,exc_r_t.`day`)times
		   ON times.id_excursion = excursions_t.id_excursion
           WHERE  excursions_t.type_excursion = 3 OR times.`day` IS NOT NULL
		   GROUP BY excursions_t.id_excursion)excursions_time
		ON '.$tags_on_exc.' ';
            if(!empty($end)){
                $SQL .= " AND (excursions_time.sort <= ".(int)$end." OR excursions_time.type_excursion = 3) ";
            }
            $SQL .= '
		GROUP BY excursions_time.id_excursion
		ORDER BY excursions_time.priority
		LIMIT '.$limit.')excursion_filter
	LEFT JOIN
		(SELECT excursion_photos.*
		FROM excursion_photos
		JOIN (SELECT id_excursion, MIN(priority) AS priority FROM excursion_photos  WHERE for_main = 1 GROUP BY id_excursion)photo_min
		ON photo_min.id_excursion = excursion_photos.id_excursion AND photo_min.priority = excursion_photos.priority
		GROUP BY excursion_photos.id_excursion)photos
	ON excursion_filter.id_excursion = photos.id_excursion)
ORDER BY sort, priority, name
LIMIT '.$limit;
            if(!empty($carousel['data_start']) && $carousel['data_start'] > time()){
                $start = $carousel['data_start'];
            }else{
                $start = explode('.',date('d.m.Y'));
                $start = mktime(0,0,0,$start[1],$start[0],$start[2]);
            }
            $carousels_obj[$carousel['id_carousel']] = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':start'=>$start));
        }
        //d::dump($carousels_obj);
        return array('view'=>'wrap_carousels', 'array'=>array('page'=>$page, 'carousels'=>$carousels, 'carousels_obj'=>$carousels_obj, 'tags'=>$this->tagsSQL()));
    }

    function getForViewList($page, $main = 0, $type=0, $start_time = 0, $end_time=0, $plitka = 1){
        //$date_current = date('d.m.Y');
        //$data_current = explode('.',$date_current);
        $arr_child_pages = $this->getArrPagesForSearch($page['id_page']);
        $pages_for_tour = '';
        $pages_for_exc = '';
        foreach($arr_child_pages AS $id_p){
            $pages_for_tour .= " tours.main_section = ".(int)$id_p." OR tours.add_section LIKE '%,".(int)$id_p.",%' OR";
            $pages_for_exc .= " excursions.main_section = ".(int)$id_p." OR excursions.add_section LIKE '%,".(int)$id_p.",%' OR";
        }
        if(!empty($pages_for_tour)){
            $pages_for_tour = 'OR ('.substr($pages_for_tour,0,-3).') ';
            $pages_for_exc = 'OR ('.substr($pages_for_exc,0,-3).') ';
        }


        $SQL = "SELECT tours_times.*, excursions_d.`day` AS data_exc FROM
                	(SELECT `type`, `start`, `end`,data_start AS data_tour, priority, name FROM
                		(	(SELECT UNIX_TIMESTAMP(NOW()) AS `start`, UNIX_TIMESTAMP(NOW()) AS `end`, 1 AS `type`, 0 AS priority, 'Периодичные' AS name)
                		UNION ALL
                			(SELECT
                				UNIX_TIMESTAMP(CONCAT_WS(',',IF(month(CURDATE()) <= id, year(CURDATE()), year(CURDATE())+1),id, '01')) AS `start`,
                				UNIX_TIMESTAMP(CONCAT_WS(',',IF(month(CURDATE()) <= id OR id+1 < 13, year(CURDATE()), year(CURDATE())+1),IF(id+1 < 13, id+1, 1), '01'))-1 AS `end`,
                				2 AS `type`,
                				0 AS priority,
                				name_month AS name
                			FROM months)
                		UNION ALL
                			(SELECT start_period AS `start`,
                				end_period AS `end`,
                				2 AS `type`,
                				priority AS priority,
									name FROM periods WHERE is_active = 1 AND end_period > UNIX_TIMESTAMP(NOW()) ORDER BY priority, name)
                		UNION ALL
                			(SELECT UNIX_TIMESTAMP(NOW()) AS `start`, UNIX_TIMESTAMP(NOW()) AS `end`, 3, 0 AS priority, 'Под заказ' AS name))periods_temp
                	LEFT JOIN
                		(SELECT tours.data_start, tours.data_end, tour_times.`start` AS start_time, tours.type_tour
                		FROM tours
                        LEFT JOIN tour_times
                        ON tour_times.id_tour = tours.id_tour
                        WHERE tours.is_active = 1 AND (tours.main_section = :id_page OR tours.add_section LIKE :id_page_like ".$pages_for_tour.")
                            AND tours.data_end >= UNIX_TIMESTAMP(NOW()))tours_d
                	ON periods_temp.`type` = tours_d.type_tour
                        AND ((tours_d.type_tour = 1 AND tours_d.data_start <= periods_temp.`end`
                            AND tours_d.data_end >= periods_temp.`start`)
                            OR (tours_d.type_tour = 2
                                AND tours_d.data_start <= periods_temp.`end`
                                AND tours_d.data_end >= periods_temp.`start`
                                AND tours_d.start_time <= periods_temp.`end`
                                AND tours_d.start_time >= periods_temp.`start`)
                            OR tours_d.type_tour = 3)
                	GROUP BY `type`, `start`, `end`)tours_times
                LEFT JOIN
                	(SELECT excursions_t.*, IF(excursions_t.type_excursion = 2, times.`day`, 1) AS `day`,end_w
                	FROM
                		(SELECT  id_excursion, type_excursion
                	    FROM excursions
                	    WHERE (excursions.main_section = :id_page OR excursions.add_section LIKE :id_page_like ".$pages_for_exc.") AND excursions.is_active = 1)excursions_t
                	LEFT JOIN
                	    (SELECT exc_route_times.id_excursion, `day`,exc_route_times.id_route,end_w
                    	FROM exc_route_times
                    	LEFT JOIN
                    		(SELECT id_excursion, id_route, id_group, `start`, `end`,
                    				IF(DATE_FORMAT(FROM_UNIXTIME(`start`),'%w') = 0, 7, DATE_FORMAT(FROM_UNIXTIME(`start`),'%w')) AS start_w,
                    				IF(DATE_FORMAT(FROM_UNIXTIME(`end`),'%w') = 0, 7, DATE_FORMAT(FROM_UNIXTIME(`end`),'%w')) AS end_w
                    		FROM
                    			(SELECT id_excursion, id_route, id_group, `start`, `end`
                    			FROM exc_route_price
                    			WHERE `start` <= :time_week AND `end` > :time)price_t
                    		)price
                    	ON exc_route_times.id_route = price.id_route
                    		AND price.id_group = exc_route_times.id_group
                    		AND (IF(start_w > exc_route_times.`day`,7-(start_w-exc_route_times.`day`),exc_route_times.`day` - start_w))*86400 + `start` < :time_week
                    		AND `end` - (IF(end_w < exc_route_times.`day`,7-(exc_route_times.`day`-end_w),end_w-exc_route_times.`day`))*86400 > :time
                    	WHERE  exc_route_times.`day` > UNIX_TIMESTAMP(NOW()) OR (exc_route_times.`day` < 8  AND end_w IS NOT NULL))times
                	ON times.id_excursion = excursions_t.id_excursion)excursions_d
                 ON  excursions_d.type_excursion = tours_times.`type`
                		AND (
                			(excursions_d.type_excursion = 2 AND excursions_d.`day` <= tours_times.`end` AND excursions_d.`day` > tours_times.`start`)
                			OR
                			(excursions_d.type_excursion = 1 AND end_w IS NOT NULL)
                			OR
                			(excursions_d.type_excursion = 3)
                		)
                GROUP BY `type`, `start`, `end`
                ORDER BY tours_times.`type`, tours_times.`start`, tours_times.priority";
        $months = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_page'=>$page['id_page'],
            ':id_page_like'=>'%,'.$page['id_page'].',%',
            ':time_week'=>time()+518400,
            ':time'=>time()));
        $i = 0;
        //d::dump($months);
        foreach($months AS $month){
            if($type != 0){
                if($type == $month['type'] && (($type == 2 && $start_time == $month['start'] && $end_time == $month['end']) || $type != 2)){
                    $i = 0;
                }else
                    $i = 1;
            }

            if($month['type'] == 1 && (!empty($month['data_tour']) || !empty($month['data_exc']))){
                $i++;
                if($i == 1)
                    $type = $month;
                $times[1] =  array('name'=>$month['name'], 'active'=>$i == 1 ? 1 : 0);
            }else if($month['type'] == 2 && (!empty($month['data_tour']) || !empty($month['data_exc']))){
                $i++;
                if($i == 1)
                    $type = $month;
                /*  if($month['priority'] == 0){
                       if((int)date('m',$month['start']) == (int)$data_current[1])
                           $month['name'] = 'Текущий месяц';
                       elseif((int)date('m',$month['start']) == $data_current[1]+1)
                           $month['name'] = 'Следующий месяц';
                   } */
                $times[2][] = array('name'=>$month['name'],
                    'active'=>$i == 1 ? 1 : 0,
                    'start_period'=>$month['start'],
                    'end_period'=>$month['end']);
            }else if($month['type'] == 3 && (!empty($month['data_tour']) || !empty($month['data_exc']))){
                $i++;
                if($i == 1)
                    $type = $month;
                $times[3] = array('name'=>$month['name'], 'active'=>$i == 1 ? 1 : 0);
            }
        }
        // d::dump($months);
        if($main)
            $limit = (int)Yii::$app->params['count_block_main'];
        else
            $limit = (int)Yii::$app->params['count_plitka'] < 1 ? 10 : (int)Yii::$app->params['count_plitka']+1;
        if(!empty($type)){
            if($plitka)
                $DataAll = $this->plitkaSQL($page['id_page'], $type['type'], $limit, 0, $type['start'], $type['end']);
            else
                $DataAll = $this->plitkaSQL($page['id_page'], $type['type'], $limit, 0, $type['start'], $type['end'], 1);
        }
        //d::dump($DataAll);
        if(!$main)
            $limit--;
        if($main){
            return array('view'=>'main_blocks', 'array'=> array('page'=>$page,
                'times'=>$times,
                'DataAll'=>$DataAll,
                'limit'=>$limit));
        }else{
            return array('view'=>'list', 'array'=> array('page'=>$page,
                'times'=>$times,
                'DataAll'=>$DataAll,
                'tags'=>$this->tagsSQL(),
                'limit'=>$limit,
                'plitka'=>$plitka));
        }
    }

    function priceSelectRoute($excursion,$select_routes){
        $price = array();
        $time = date('d.m.Y');
        $time = explode('.',$time);
        $time = mktime(0,0,0,$time[1],$time[0],$time[2]);
        if($excursion['type_excursion'] == 1 && !empty($select_routes)){
            $where = '';
            foreach($select_routes AS $route){
                $where .= 'id_route = '.(int)$route['id_route'].' OR ';
            }
            $where = substr($where,0,-3);
            $SQL = "SELECT * FROM
                    	(SELECT routes.`day`, routes.`times`,
                    			price.*, (IF(start_w > routes.`day`,7-(start_w-routes.`day`),routes.`day` - start_w))*86400 + `start` AS first_start,
                    			`end` - (IF(end_w < routes.`day`,7-(routes.`day`-end_w),end_w-routes.`day`))*86400 AS last_end
                    	FROM
                    		(SELECT `day`,id_route,id_group, (GROUP_CONCAT(`time` ORDER BY `time` SEPARATOR  '&'))`times`
                    		FROM exc_route_times
                    		WHERE ".$where."
                    		GROUP BY id_route,`day`,id_group)routes
                    	JOIN
                    		(SELECT id_str,id_route,IF(`start` > :time, `start`, :time) AS `start`,`end`,id_cat_price,price,id_excursion,id_group,
                    				IF(DATE_FORMAT(FROM_UNIXTIME(IF(`start`> :time ,`start`, :time)),'%w') = 0,
                                        7, DATE_FORMAT(FROM_UNIXTIME(IF(`start`> :time,`start`,:time)),'%w')) AS start_w,
				                    IF(DATE_FORMAT(FROM_UNIXTIME(`end`),'%w') = 0, 7, DATE_FORMAT(FROM_UNIXTIME(`end`),'%w')) AS end_w
                    		FROM exc_route_price
                    		WHERE `end` >= :time)price
                    	ON routes.id_route = price.id_route
                    		AND price.id_group = routes.id_group)t
                    WHERE last_end >= first_start AND last_end >= :time";
            $time_price = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':time'=>$time));
            foreach($time_price AS $p){

                //if(!isset($price[$p['id_route']]['arr'][$p['first_start']]))
                // d::dump($p, false);
                if(!isset($price[$p['id_route']]['min_start']) || $price[$p['id_route']]['min_start'] > $p['first_start']){
                    $price[$p['id_route']]['min_start'] = $p['first_start'];
                }
                if(!isset($price[$p['id_route']]['max_start']) || $price[$p['id_route']]['max_start'] < $p['last_end']){
                    $price[$p['id_route']]['max_start'] = $p['last_end'];
                }
                if(!isset($price[$p['id_route']]['arr'][$p['first_start']]['max_start']) || $price[$p['id_route']]['arr'][$p['first_start']]['max_start'] < $p['last_end']){
                    $price[$p['id_route']]['arr'][$p['first_start']]['max_start'] = $p['last_end'];
                }
                //$price[$p['id_route']]['arr'][$p['first_start']]['end'][$p['last_end']] = $p['last_end'];
                $price[$p['id_route']]['arr'][$p['first_start']]['times'][$p['times']] = $p['times'];
                $price[$p['id_route']]['arr'][$p['first_start']]['price'][$p['id_str']][$p['id_cat_price']] = $p['price'];
            }
            //d::dump($price);
        }else if($excursion['type_excursion'] == 2 && !empty($select_routes)){
            $where = '';
            foreach($select_routes AS $route){
                $where .= 'id_route = '.(int)$route['id_route'].' OR ';
            }
            $where = substr($where,0,-3);
            $SQL = "SELECT routes.`day`, routes.`times`, price.*
           	        FROM
                    		(SELECT `day`,id_route,id_group, (GROUP_CONCAT(`time` ORDER BY `time` SEPARATOR  '&'))`times`
                    		FROM exc_route_times
                    		WHERE `day`>= :time AND (".$where.")
                    		GROUP BY id_route,`day`,id_group)routes
                    JOIN
                    		(SELECT id_str,id_route,IF(`start` > :time, `start`, :time) AS `start`,`end`,id_cat_price,price,id_excursion,id_group
                    		FROM exc_route_price
                    		WHERE `end` >= :time)price
                    ON routes.id_route = price.id_route
                    		AND price.id_group = routes.id_group
                            AND routes.`day` <= price.`end` AND routes.`day` >= price.`start`";
            $time_price = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':time'=>$time));
            foreach($time_price AS $p){
                if(!isset($price[$p['id_route']]['min_start']) || $price[$p['id_route']]['min_start'] > $p['day']){
                    $price[$p['id_route']]['min_start'] = $p['day'];
                }
                if(!isset($price[$p['id_route']]['max_start']) || $price[$p['id_route']]['max_start'] < $p['day']){
                    $price[$p['id_route']]['max_start'] = $p['day'];
                }
                //$price[$p['id_route']]['arr'][$p['first_start']]['end'][$p['last_end']] = $p['last_end'];
                $price[$p['id_route']]['arr'][$p['day']]['times'][$p['times']] = $p['times'];
                $price[$p['id_route']]['arr'][$p['day']]['price'][$p['id_str']][$p['id_cat_price']] = $p['price'];
            }
        }
        return $price;
    }

    function priceTourSQL($times, $tour){
        if(!empty($times)){
            $where_group = '';
            foreach($times AS $time){
                $where_group .= ' id_group = '.(int)$time['id_group'].' OR';
            }
            $where_group = substr($where_group,0,-2);
            /** Запрос цен на выбранную дату */
            $SQL = 'SELECT * FROM
                    	(SELECT hotels_tour.id_hotel, time_price.id_time_price  FROM
                    		(SELECT * FROM tour_hotels WHERE ('.$where_group.') AND id_tour = :id_tour)hotels_tour
                    	JOIN
                    		(SELECT * FROM tour_time_price WHERE tour_time_price.`start` < :data_tour AND tour_time_price.`end` > :data_tour)time_price
                    	ON hotels_tour.id_tour_hotel = time_price.id_tour_hotel)h_periods_price
                    LEFT JOIN
                    	tour_price
                    ON h_periods_price.id_time_price = tour_price.id_time_price
                    ORDER BY id_breakfast';
            $price_t = Yii::$app->db->createCommand($SQL)->queryAll(true, array(':data_tour'=>$times[0]['start'], ':id_tour'=>$tour['id_tour']));
            foreach($price_t AS $p){
                $price[$p['id_hotel']][$p['id_room']][$p['id_breakfast']][$p['id_cat_price']] = $p['price'];
                $id_hotels[$p['id_hotel']] = $p['id_hotel'];
            }
            /** Запрос отелей и комнат*/
            if(!empty($id_hotels)){
                $where = '';
                foreach($id_hotels AS $id){
                    $where .= ' id_hotel = '.(int)$id.' OR';
                }
                $where = substr($where,0,-2);
                $SQL = 'SELECT hotels_need.name, rooms.id_hotel, rooms.name AS room_name, rooms.id_room FROM
                        	(SELECT hotels.name,  hotels.id_hotel, hotels.priority AS priority_hotel FROM hotels WHERE'.$where.')hotels_need
                        LEFT JOIN
                        	rooms
                        ON hotels_need.id_hotel = rooms.id_hotel
                        ORDER BY priority_hotel, priority';
                $hotels_temp = Yii::$app->db->createCommand($SQL)->queryAll();
                foreach($hotels_temp AS $hotel){
                    if(isset($price[$hotel['id_hotel']][$hotel['id_room']])){
                        $hotels[$hotel['id_hotel']]['name'] = $hotel['name'];
                        $hotels[$hotel['id_hotel']]['rooms'][$hotel['id_room']]['name'] = $hotel['room_name'];
                        $hotels[$hotel['id_hotel']]['rooms'][$hotel['id_room']]['breakfast'] = $price[$hotel['id_hotel']][$hotel['id_room']];
                    }
                }
            }
        }
        return $hotels;
    }

    function eventsSQL($id_page,$limit,$limit_start = 0){
        $SQL = 'SELECT * FROM news WHERE main_section = :id_page AND is_active = 1 ORDER BY `data` DESC LIMIT '.(int)$limit_start.','.(int)$limit;
        return Yii::$app->db->createCommand($SQL)->queryAll(true, array(':id_page'=>$id_page));
    }

    function categoriesTourSQL($tour){
        if(!empty($tour['price_category'])){
            $arr_cat = explode(',', $tour['price_category']);
            if(!empty($arr_cat)){
                $where = '';
                foreach($arr_cat AS $id_cat){
                    $where .= ' id_category = '.(int)$id_cat.' OR';
                }
                $where = substr($where,0,-2);
                $SQL = 'SELECT * FROM price_category_tour WHERE'.$where.' ORDER BY priority';
                $cat_price = Yii::$app->db->createCommand($SQL)->queryAll();
                foreach($cat_price AS $cat){
                    $categories[$cat['id_category']] = $cat;
                }
            }
        }
        return $categories;
    }

    function categoriesExcSQL($excur){
        if(!empty($excur['price_category'])){
            $arr_cat = explode(',', $excur['price_category']);
            if(!empty($arr_cat)){
                $where = '';
                foreach($arr_cat AS $id_cat){
                    $where .= ' id_category = '.(int)$id_cat.' OR';
                }
                $where = substr($where,0,-2);
                $SQL = 'SELECT * FROM price_category_excursion WHERE'.$where.' ORDER BY priority';
                $cat_price = Yii::$app->db->createCommand($SQL)->queryAll();
                foreach($cat_price AS $cat){
                    $categories[$cat['id_category']] = $cat;
                }
            }
        }
        return $categories;
    }

    function servicesSQL($str){
        $str = explode(',', $str);
        if(!empty($str)){
            $where = '';
            foreach($str AS $id_service){
                $where .= ' id_service = '.(int)$id_service.' OR';
            }
            $where = substr($where,0,-3);
            $SQL = 'SELECT * FROM services WHERE is_active = 1 AND ('.$where.') ORDER BY priority';
            $services = Yii::$app->db->createCommand($SQL)->queryAll();
        }else
            $services = array();
        return $services;
    }

    function conditionsSQL($str){
        $str = explode(',', $str);
        if(!empty($str)){
            $where = '';
            foreach($str AS $id_condition){
                $where .= ' id_condition = '.(int)$id_condition.' OR';
            }
            $where = substr($where,0,-3);
            $SQL = 'SELECT * FROM conditions WHERE is_active = 1 AND ('.$where.') ORDER BY priority';
            $conditions = Yii::$app->db->createCommand($SQL)->queryAll();
        }else
            $conditions = array();
        return $conditions;
    }

    function paymentsSQL($str){
        $str = explode(',', $str);
        if(!empty($str)){
            $where = '';
            foreach($str AS $id_payment){
                $where .= ' id_payment = '.(int)$id_payment.' OR';
            }
            $where = substr($where,0,-3);
            $SQL = 'SELECT * FROM payments WHERE is_active = 1 AND ('.$where.') ORDER BY priority';
            $payments = Yii::$app->db->createCommand($SQL)->queryAll();
        }else
            $payments = array();
        return $payments;
    }

    function faqSQL($str){
        $str = explode(',', $str);
        if(!empty($str)){
            $where = '';
            foreach($str AS $id_faq){
                $where .= ' id_faq = '.(int)$id_faq.' OR';
            }
            $SQL = 'SELECT * FROM FAQ WHERE'.$where.' `default` = 1 ORDER BY priority';
            $faq = Yii::$app->db->createCommand($SQL)->queryAll();
        }else
            $faq = array();
        return $faq;
    }

    function plitkaSQL($id_page, $type, $limit, $start_limit = 0, $start = 0, $end = 0, $rows = 0){
        // d::dump($this->getArrPagesForSearch($id_page));
        $arr_child_pages = $this->getArrPagesForSearch($id_page);
        $pages_for_tour = '';
        $pages_for_exc = '';
        foreach($arr_child_pages AS $id_p){
            $pages_for_tour .= ' tours.main_section = '.(int)$id_p.' OR tours.add_section LIKE "%,'.(int)$id_p.',%" OR';
            $pages_for_exc .= ' excursions.main_section = '.(int)$id_p.' OR excursions.add_section LIKE "%,'.(int)$id_p.',%" OR';
        }
        if(!empty($pages_for_tour)){
            $pages_for_tour = 'OR ('.substr($pages_for_tour,0,-3).') ';
            $pages_for_exc = 'OR ('.substr($pages_for_exc,0,-3).') ';
        }
        //$this->getArrPagesForSearch($id_page);
        if((int)$limit < 1)
            $limit = 9;
        /** Это пиздец-запрос. Он обединяет в себе основную таблицу туров, табличку фоток, общую таблицу тегов и таблицу тегов туров,
         *  табличку дат начал туров
        и находит действительные или ближайщие цены из которых выбирает основные цены, а потом из них самую низкую по цене
        для этого обращается к таблицам:периодов цен, категорий цен туров и самих цен
        Так же сортирует туры по расписанию, приоритету и имени */
        if($rows){
            $array_for_sql = array('columns'=>' ,id_guide,short_text',
                'tour'=>' tours_time.id_guide, tours_time.short_text,',
                'exc'=>' routes.length, routes.id_guide, excursions_time.short_text,',
                'exc_route'=>'LEFT JOIN
                                                    	(SELECT excursion_routes.length,  excursion_routes.id_guide, excursion_routes.id_excursion
                                                    	FROM excursion_routes
                                                    	JOIN (SELECT id_excursion, MIN(priority) AS priority FROM excursion_routes GROUP BY id_excursion)route_min
                                                    	ON route_min.id_excursion = excursion_routes.id_excursion AND route_min.priority = excursion_routes.priority
                                                        GROUP BY excursion_routes.id_excursion)routes
                                                   ON excursions_time.id_excursion = routes.id_excursion ',);
        }else{
            $array_for_sql = array('columns'=>'',
                'tour'=>'',
                'exc'=>' null,',
                'exc_route'=>'',);
        }
        $timestamp_day_now = date('d.m.Y');
        $timestamp_day_now = explode('.',$timestamp_day_now);
        $timestamp_day_now = mktime(0,0,0,$timestamp_day_now[1],$timestamp_day_now[0],$timestamp_day_now[2]);
        if($type == 1){
            $sort = $this->ArraySortDay(date('w'));

            $sql_start_tour = '(SELECT tours_time.id_tour AS id, tours_time.name, tours_time.alias, tours_time.priority,
                                (IF(tours_time.`start` IS NULL ,0, tours_time.`start`))`start`, tours_time.main_section,
                            	tours_time.data_start, tours_time.data_end, tours_time.length,'.$array_for_sql['tour'].'
                            	(photos.file_name)ph_file_name, price_min.price, tags_tour.tags_str
                            FROM
                            	(SELECT tours_t.*, (GROUP_CONCAT(DISTINCT tour_times.`start` ORDER BY FIELD(tour_times.`start`, '.$sort.')))`start`
                                FROM
                            		(SELECT id_tour, name, alias, priority, data_end, data_start, length, main_section'.$array_for_sql['columns'].'
                            		FROM tours
                            		WHERE (tours.main_section = :id_page OR tours.add_section LIKE :id_page_like '.$pages_for_tour.')
                                            AND tours.type_tour = :type AND tours.is_active = 1 AND data_end > :start AND data_start <= :start)tours_t
                                JOIN
                                    tour_times
                            	ON tour_times.id_tour = tours_t.id_tour
                            	GROUP BY tour_times.id_tour)tours_time';
            $sql_start_exc = '(SELECT excursions_time.id_excursion AS id, excursions_time.name, excursions_time.alias, excursions_time.priority,
                                        (IF(excursions_time.`start` IS NULL ,0, excursions_time.`start`))`start`, excursions_time.main_section,
                                		null, null,'.$array_for_sql['exc'].'
                                		(photos.file_name)ph_file_name, excursions_time.price, tags_excursion.tags_str
                                FROM
                                	(SELECT excursions_t.*, (GROUP_CONCAT(times.`day`,"&",times.times ORDER BY FIELD(times.`day`, '.$sort.')))`start`, times.price
                                    FROM
                                		(SELECT  id_excursion, name, alias, priority, main_section'.$array_for_sql['columns'].'
                                		FROM excursions
                                		WHERE (excursions.main_section = :id_page OR excursions.add_section LIKE :id_page_like '.$pages_for_exc.')
                                        AND excursions.type_excursion = :type AND excursions.is_active = 1)excursions_t
                                	JOIN
                                		(SELECT exc_route_times.id_excursion, exc_route_times.`day`,
                                                (GROUP_CONCAT(`time` ORDER BY `time` SEPARATOR  "&"))`times`, price_str.price
                                		FROM exc_route_times
                                        JOIN
                                           (SELECT id_excursion, id_route, id_group, `start`, `end`,start_w,end_w, GROUP_CONCAT(temp_price.price ORDER BY temp_price.priority)price
                                        	FROM
                                        		(SELECT id_excursion, price, priority, id_route, id_group, `start`, `end`,
                                        			IF(DATE_FORMAT(FROM_UNIXTIME(`start`),"%w") = 0, 7, DATE_FORMAT(FROM_UNIXTIME(`start`),"%w")) AS start_w,
                                        			IF(DATE_FORMAT(FROM_UNIXTIME(`end`),"%w") = 0, 7, DATE_FORMAT(FROM_UNIXTIME(`end`),"%w")) AS end_w
                                        		FROM
                                        			(SELECT id_excursion, price, id_cat_price, id_route, id_group, `start`, `end`
                                        			FROM exc_route_price
                                        			WHERE `start` <= :time_week AND `end` > :start)price
                                        		JOIN
                                        			(SELECT * FROM price_category_excursion WHERE `default` = 1)category
                                        		ON price.id_cat_price = category.id_category)temp_price
                                        	GROUP BY id_excursion, id_route, id_group)price_str
                                        ON exc_route_times.id_route = price_str.id_route
                                            AND price_str.id_group = exc_route_times.id_group
                                            AND (IF(start_w > exc_route_times.`day`,7-(start_w-exc_route_times.`day`),exc_route_times.`day` - start_w))*86400 + `start` < :time_week
                                            AND `end` - (IF(end_w < exc_route_times.`day`,7-(exc_route_times.`day`-end_w),end_w-exc_route_times.`day`))*86400 > :start
                                        GROUP BY exc_route_times.id_excursion,exc_route_times.`day`)times
                                	ON times.id_excursion = excursions_t.id_excursion
                                	GROUP BY excursions_t.id_excursion)excursions_time';
            $sql_end_union = 'ORDER BY FIELD(`start`, '.$sort.'), priority, name';
            $arr_pdo = array(':id_page'=>$id_page,
                ':id_page_like'=>'%,'.$id_page.',%',
                ':type'=>$type,
                ':start'=>$timestamp_day_now,
                ':time_week'=>$timestamp_day_now+518400);
        }elseif($type == 2){
            $sql_start_tour = '(SELECT tours_time.id_tour AS id, tours_time.name, tours_time.alias, tours_time.priority,
                                (IF(tours_time.`start` IS NULL ,9, tours_time.`start`))`start`, tours_time.main_section,
                            	tours_time.data_start, tours_time.data_end, tours_time.length,'.$array_for_sql['tour'].'
                            	(photos.file_name)ph_file_name, price_min.price, tags_tour.tags_str
                            FROM
                            	(SELECT tours_t.*, (GROUP_CONCAT(tour_times_w.`start` ORDER BY tour_times_w.`start`))`start`
                                FROM
                            		(SELECT id_tour, name, alias, priority, data_end, data_start, length, main_section'.$array_for_sql['columns'].'
                            		FROM tours
                            		WHERE (tours.main_section = :id_page OR tours.add_section LIKE :id_page_like '.$pages_for_tour.')
                                            AND tours.type_tour = :type AND tours.is_active = 1 AND data_end >= :start AND data_start <= :end)tours_t
                            	JOIN
                            		(SELECT * FROM tour_times WHERE start >= :start)tour_times_w


                            	ON tour_times_w.id_tour = tours_t.id_tour
                            	GROUP BY tour_times_w.id_tour)tours_time';
            $sql_start_exc = '(SELECT excursions_time.id_excursion AS id, excursions_time.name, excursions_time.alias, excursions_time.priority,
                                        (IF(excursions_time.`start` IS NULL ,9, excursions_time.`start`))`start`, excursions_time.main_section,
                                		null, null,'.$array_for_sql['exc'].'
                                		(photos.file_name)ph_file_name, price_str.price, tags_excursion.tags_str
                                FROM
                                	(SELECT excursions_t.*, (GROUP_CONCAT(times.`day`,"&",times.times ORDER BY times.`day`))`start`
                                    FROM
                                		(SELECT  id_excursion, name, alias, priority, main_section'.$array_for_sql['columns'].'
                                		FROM excursions
                                		WHERE (excursions.main_section = :id_page OR excursions.add_section LIKE :id_page_like '.$pages_for_exc.') AND excursions.type_excursion = :type AND excursions.is_active = 1)excursions_t
                               	    JOIN
                                		(SELECT exc_route_times.id_excursion, exc_route_times.`day`,
                                                (GROUP_CONCAT(`time` ORDER BY `time` SEPARATOR  "&"))`times`
                                		FROM exc_route_times
                							WHERE  exc_route_times.`day` >= :start AND exc_route_times.`day` <= :end
                                		GROUP BY exc_route_times.id_excursion,exc_route_times.`day`)times
                                	ON times.id_excursion = excursions_t.id_excursion
                                	GROUP BY times.id_excursion)excursions_time';
            $sql_end_union = 'ORDER BY `start`, priority, name';
            $arr_pdo = array(':id_page'=>$id_page,
                ':id_page_like'=>'%,'.$id_page.',%',
                ':type'=>$type,
                ':start'=>$start>$timestamp_day_now ? $start : $timestamp_day_now,
                ':end'=>$end,);
        }elseif($type == 3){
            $sql_start_tour = '(SELECT tours_time.id_tour AS id, tours_time.name, tours_time.alias, tours_time.priority, tours_time.main_section,
                                    tours_time.data_start, tours_time.data_end, tours_time.start_price, tours_time.length,'.$array_for_sql['tour'].'
                	               (photos.file_name)ph_file_name, tags_tour.tags_str
                                FROM
                                    (SELECT id_tour, name, alias, priority, data_end, data_start, main_section,length, `start_price`'.$array_for_sql['columns'].'
     						         FROM tours
                               	    WHERE
                                        (tours.main_section = :id_page OR tours.add_section LIKE :id_page_like '.$pages_for_tour.')
                                        AND tours.type_tour = :type
                                        AND tours.is_active = 1
                                        AND data_end >= :time)tours_time';
            $sql_start_exc = '(SELECT excursions_time.id_excursion AS id, excursions_time.name, excursions_time.alias, excursions_time.priority, excursions_time.main_section,
                                      null, null,excursions_time.start_price,'.$array_for_sql['exc'].'
                		              (photos.file_name)ph_file_name, tags_excursion.tags_str
                               FROM
                                    (SELECT  id_excursion, name, alias, priority, main_section,start_price'.$array_for_sql['columns'].'
                	                   FROM excursions
                	                   WHERE (excursions.main_section = :id_page OR excursions.add_section LIKE :id_page_like '.$pages_for_exc.')
                                            AND excursions.type_excursion = :type AND excursions.is_active = 1)excursions_time';
            $sql_end_union = 'ORDER BY priority, name';
            $arr_pdo = array(':id_page'=>$id_page,
                ':id_page_like'=>'%,'.$id_page.',%',
                ':type'=>$type,
                ':time'=>$timestamp_day_now);
        }
        $SQL = $sql_start_tour.'
                    LEFT JOIN
                    	(SELECT tour_photos.*
                    	FROM tour_photos
                    	JOIN (SELECT id_tour, MIN(priority) AS priority FROM tour_photos GROUP BY id_tour)photo_min
                    	ON photo_min.id_tour = tour_photos.id_tour AND photo_min.priority = tour_photos.priority
                        GROUP BY tour_photos.id_tour)photos
                    ON tours_time.id_tour = photos.id_tour ';
        if($type != 3)
            $SQL .= 'LEFT JOIN
                       (SELECT * FROM
                    		(SELECT tour_time_price.id_tour, price.price
                    		FROM tour_time_price
                    		RIGHT JOIN
                    		   (SELECT tour_price.id_time_price, tour_price.price
                    		   FROM tour_price
                    		   RIGHT JOIN
                    				(SELECT id_category FROM price_category_tour WHERE `default` = 1)category
                    		   ON category.id_category = tour_price.id_cat_price)price
                    		ON price.id_time_price = tour_time_price.id_time_price
                    		WHERE tour_time_price.`end` > :start AND price.price > 0
                            ORDER BY tour_time_price.`start`, price.price)price_main_order
                    	GROUP BY id_tour)price_min
                    ON tours_time.id_tour = price_min.id_tour ';
        $SQL .=     'LEFT JOIN
                    	(SELECT id_tour, (GROUP_CONCAT(DISTINCT tags_order.id_tag ORDER BY tags_order.priority))tags_str
                    	FROM
                    		(SELECT tour_tags.*, tags.priority
                    		FROM tour_tags
                    		JOIN tags
                    		ON tour_tags.id_tag = tags.id_tag)tags_order
                    	GROUP BY id_tour)tags_tour
                    ON tags_tour.id_tour = tours_time.id_tour)
                UNION ALL
                    '.$sql_start_exc.'
                    LEFT JOIN
                    	(SELECT excursion_photos.*
                    	FROM excursion_photos
                    	JOIN (SELECT id_excursion, MIN(priority) AS priority FROM excursion_photos  WHERE for_main = 1 GROUP BY id_excursion)photo_min
                    	ON photo_min.id_excursion = excursion_photos.id_excursion AND photo_min.priority = excursion_photos.priority
                        GROUP BY excursion_photos.id_excursion)photos
                    ON excursions_time.id_excursion = photos.id_excursion '.$array_for_sql['exc_route'];
        if($type == 2)
            $SQL .= 'LEFT JOIN
                       (SELECT id_excursion, GROUP_CONCAT(temp_price.price ORDER BY temp_price.priority)price
                    	FROM
                    		(SELECT id_excursion, price, priority
                    		FROM
                    			(SELECT price, id_cat_price, id_excursion
                    			FROM exc_route_price
                    			WHERE `start` <= :end AND `end` >= :start)price
                    		JOIN
                    			(SELECT * FROM price_category_excursion WHERE `default` = 1)category
                    		ON price.id_cat_price = category.id_category)temp_price
                    	GROUP BY id_excursion)price_str
                    ON excursions_time.id_excursion = price_str.id_excursion ';
        $SQL .=    'LEFT JOIN
                    	(SELECT id_excursion, (GROUP_CONCAT(DISTINCT tags_order.id_tag ORDER BY tags_order.priority))tags_str
                    	FROM
                    		(SELECT excursion_tags.*, tags.priority
                    		FROM excursion_tags
                    		JOIN tags
                    		ON excursion_tags.id_tag = tags.id_tag)tags_order
                    	GROUP BY id_excursion)tags_excursion
                    ON tags_excursion.id_excursion = excursions_time.id_excursion)
                '.$sql_end_union.'
                LIMIT '.(int)$start_limit.', '.(int)$limit;
        return Yii::$app->db->createCommand($SQL)->queryAll(true, $arr_pdo);
    }
}
?>
