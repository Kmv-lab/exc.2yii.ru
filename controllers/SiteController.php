<?php

namespace app\controllers;

use app\models\Sitemap;
use app\modules\adm\models\ExcursionComments;
use app\modules\adm\models\Main_page;
use app\modules\adm\models\Rooms;
use app\modules\adm\models\SanBlocks;
use app\widgets\ExcursionsWidget;
use yii\helpers\Url;
use yii\jui\Widget;
use yii\web\Controller;
use Yii;
use app\models\SansPrev;
use app\commands\PagesHelper;
use yii\web\NotFoundHttpException;
use app\commands\helpers;
/*use app\widgets\Pagination;
use app\modules\adm\controllers\SanatoriumController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\adm\models\StaticSeo;
use app\models\ContactForm;
use app\modules\adm\models\News;
use app\modules\adm\models\Testi;*/

class SiteController extends Controller{

    /*public function actionIndex(){
        $this->layout = 'main';
        helpers::createSEO(StaticSeo::findOne(1));
        $news = News::find()->where(['type'=>1, 'is_active'=>1])
            ->orderBy('date_publication DESC')->limit(1)->all();
        $blog = News::find()->where(['type'=>2, 'is_active'=>1])
            ->orderBy('date_publication DESC')->limit(4)->all();
        $art = News::find()->where(['type'=>3, 'is_active'=>1])
            ->orderBy('date_publication DESC')->limit(1)->all();
        $testi = Testi::find()->where(['is_active'=>1, 'for_main'=>1])
            ->orderBy('data DESC')->limit(3)->all();
        return $this->render('index', ['news'=>$news, 'blog'=>$blog, 'art'=>$art, 'testi'=>$testi]);
    }

    public function actionContacts(){

        $this->layout = 'page_full';
        $seo = StaticSeo::findOne(8);
        helpers::createSEO($seo);
        Yii::$app->params['breadcrumbs'][] = ['label' => $seo['seo_title']];
        return $this->render('contacts');
    }*/

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
//d::dump($exception);
        if ($exception !== null) {
            $statusCode = $exception->statusCode;
            $message = $exception->getMessage();
            $this->layout = 'page';

            helpers::createSeo([], 'Ошибка!', 'Ошибка!');
            return $this->render('error', [
                'exception' => $exception,
                'statusCode' => $statusCode,
                'message' => $message
            ]);
        }
        return false;
    }

    /*public function actionReviews($page = 1){
        $this->layout = 'page';
        $model = new Testi;//подключение класса формы из админ.

        $session = Yii::$app->session;
        $session->open();
        if($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){
            $model->is_active = 0;
            $model->data = date('d.m.Y');
            if($model->save())
                $session['testi'] = 1;
            $session->close();
            return json_encode('ok');
        }
        $session['testi'] = 0;
        $send_testi = false;
        if(isset($session['testi']) && $session['testi'])
            $send_testi = true;
        $session->close();

        $page_arr = Yii::$app->params['pages'][62];

        //vd($page_arr);

        if(!empty($page_arr['seo_title']))
            $page_arr['seo_title'] .= ' - Страница '.$page;
        helpers::createSEO($page_arr, $page_arr['page_name'].' - Страница '.$page, $page_arr['page_name']);
        Yii::$app->params['breadcrumbs'][] = ['label' => empty(Yii::$app->params['pages'][55]['page_breadcrumbs_name'])
            ? Yii::$app->params['pages'][55]['page_menu_name'] : Yii::$app->params['pages'][55]['page_breadcrumbs_name'],
            'url'=>PagesHelper::getUrlById(55)];
        Yii::$app->params['breadcrumbs'][] = ['label' => empty($page_arr['page_breadcrumbs_name']) ? $page_arr['page_menu_name'] : $page_arr['page_breadcrumbs_name']];

        $request = $this->reviews_request(false,$page);//создание sql запроса

        //vd($request);

        $testi = Yii::$app->db->createCommand($request['sql'])->bindValues($request['params'])->queryAll();//выгрузка из бд отзывов

        $request = $this->reviews_request();
        $count = Yii::$app->db->createCommand($request['sql'])->bindValues($request['params'])->queryScalar();

        // отправка на отрисовку с данными

        //vd($testi);
        return $this->render('reviews', ['testi'=>$testi, 'page'=>$page, 'count'=>$count, 'model'=>$model, 'send_testi'=>$send_testi]);
    }

    public function actionReviews2(){
        helpers::createSEO('','Отзывы','отзывы');
        $this->layout = 'page';
        return $this->render('');
    }*/

    public function actionPage(){

        $this->layout       = 'page';
        // Получаем массив страниц соответствующий текущему url, отправляем URL и все-все страницы
        $urlArr = explode('/',Yii::$app->request->pathInfo);//массив родительских страниц

        array_pop($urlArr);//удаление последнего элемента массива, он пуст.

        $okURL  = PagesHelper::getPagesInUrl($urlArr);

        if(!$okURL)
            throw new NotFoundHttpException('Страница не найдена');
        
        $currentPage  =   $okURL[count($okURL)-1];

        $SQL = 'SELECT page_content FROM pages WHERE id_page = :id';
        $page_ext = Yii::$app->db->createCommand($SQL)->bindValue(':id', $currentPage['id_page'])->queryOne();

        $currentPage  = array_merge($currentPage,$page_ext);

        Yii::$app->params['breadcrumbs'] = PagesHelper::generateBreadcrumbs($okURL);

        //============ Обработка вызовов виджетов в тексте
        helpers::createSeo($currentPage, $currentPage['page_name'], $currentPage['page_name']);

        $currentPage['page_content'] = helpers::checkForWidgets($currentPage['page_content']);

        if(!empty($currentPage['file_name'])){
            Yii::$app->params['photo'] = Yii::$app->params['path_to_pages_images'].$currentPage['file_name'];
        }
        return $this->render('page',['page'=>$currentPage]);
    }

    /*public function actionContactform(){
        $model = new ContactForm();
        $post = Yii::$app->request->post();
        $post['ContactForm'] = current($post['ContactForm']);
        if($model->load($post) && Yii::$app->request->isAjax){
            if($model->send())
                return json_encode('ok');
        }
        return false;
    }

    public function actionSendphone(){
        if(Yii::$app->request->isAjax){
            $model = new ContactForm();
            $model->name = 'Нет имени';
            $model->office = 100;
            $model->form_name = 'Нужна помощь в подборе тура?';
            $post = Yii::$app->request->post();
            if(isset($post['phone'])){
                $model->phone = '+'.substr($post['phone'], 1);
                if($model->send())
                    return json_encode('ok');
            }
        }
        return false;

    }*/

    /**
     *
     *
     * @param $count true/false
     *
     * @return sql-запрос (str)
     */
   /* function reviews_request($count = true, $page = 1){//page == 0 выберет все без пагинации; count
        $params = [];
        $limit = '';
        if(!$count && $page != 0){
            $count_item = (int)Yii::$app->params['count_testi_items'];//приведение к int, 
            //vd(Yii::$app->params);
            $limit = ' LIMIT '.($page-1)*$count_item.','.$count_item;
        }
        $where = ' WHERE is_active = 1';
        $order = ' ORDER BY data DESC';
        $select = $count ? 'count(id)' :'*';
        $SQL = 'SELECT '.$select.'
                FROM testi'.$where.$order.$limit;
        return ['sql'=>$SQL, 'params'=>$params];
    }

    public function actionStyle(){

        $this->layout = 'page';

        return $this->render('style');
    }*/

    //___________________________________________________________________________________________________________________

    public function actionIndex(){

        $blocks = Main_page::find()->orderBy('priority')->all();

        foreach ($blocks as $key=>$block){
            $blocks[$key]['content'] = helpers::checkForWidgets($block->content);
            //vd($blocks[$key]['content']);
        }

        return $this->render('dnd', [
            'blocks' => $blocks,
        ]);
    }

    public function actionAjaxForm(){
        if (Yii::$app->request->isAjax){
            vd(Yii::$app->request->post());
        }
    }

    public function actionContacts(){

        $this->CreateSeo();

        $paramsPage = $this->getPageInfo();

        return $this->render('contacts');
    }

    public function actionFaq(){

        $this->CreateSeo();

        $paramsPage = $this->getPageInfo();

        return $this->render('faq');
    }

    public function actionReviews(){

        $this->CreateSeo();

        $paramsPage = $this->getPageInfo();
        $showMoreReviews = false;
        $SQL = 'SELECT COUNT(*) FROM `excursions`;';
        $countReviews = Yii::$app->db->createCommand($SQL)->queryOne();
        $countReviews = $countReviews['COUNT(*)'];
        if($countReviews > Yii::$app->params['count_reviews_item'])
            $showMoreReviews = true;


        $reviews = ExcursionComments::find()->orderBy('date')->limit(Yii::$app->params['count_reviews_item'])->all();

        $idExcs = array_column($reviews, 'id_exc');

        $idExcs = array_unique($idExcs);

        foreach ($idExcs as $idExc){
            $SQL = 'SELECT `name` FROM `excursions` WHERE `id` = :id';
            $excNames[$idExc] = Yii::$app->db->createCommand($SQL)->bindValue(':id', $idExc)->queryOne();
        }

        return $this->render('reviews', ['reviews' => $reviews, 'excNames' => $excNames, 'showMoreReviews' => $showMoreReviews]);
    }

    public function actionGet_more_reviews($last_reviews){

        $showMoreReviews = false;

        $SQL = 'SELECT COUNT(*) FROM `excursions`;';
        $countReviews = Yii::$app->db->createCommand($SQL)->queryOne();
        $countReviews = $countReviews['COUNT(*)'];
        if($countReviews > Yii::$app->params['added_reviews_item']+$last_reviews)
            $showMoreReviews = true;

        $reviews = ExcursionComments::find()->orderBy('date')->offset($last_reviews)->limit(Yii::$app->params['added_reviews_item'])->all();

        $idExcs = array_column($reviews, 'id_exc');

        $idExcs = array_unique($idExcs);

        foreach ($idExcs as $idExc){
            $SQL = 'SELECT `name` FROM `excursions` WHERE `id` = :id';
            $excNames[$idExc] = Yii::$app->db->createCommand($SQL)->bindValue(':id', $idExc)->queryOne();
        }

        $resultHTML='';
        $i = $last_reviews+1;
        foreach ($reviews as $review){
            $resultHTML .= $this->renderPartial('excursionsHelpers/reviewsElem', ['excName' => $excNames[$review->id_exc]['name'], 'review' => $review, 'id' =>$i++]);
        }

        $arrayNewBlocksAndStanding = [
            'showMore' => $showMoreReviews,
            'code' => htmlspecialchars_decode($resultHTML)
        ];

        $result = json_encode($arrayNewBlocksAndStanding);

        return $result;

    }

    public function actionSitemap(){
        $arrAliases = $this->generateUrls();

        return $this->renderPartial('sitemap', compact('arrAliases'));
    }

    /**
     *
     * Генерирует ссылки из БД для создания SiteMap
     *
     *
     * @return array - array url'ы динамических страниц
     */
    private function generateUrls(){
        $db_static_pages = new Sitemap;
        $arrPages = $db_static_pages->getStatickPages();

        $alias[]= Url::home(true);

        foreach ($arrPages as  $key=>$value){
            $alias [] = Url::home(true) . $this->getAliasOnStaticPage($arrPages, $key);
        }

        //для динамических страниц нужно создать правильную структуру в Sitemap->arrayOfDynamicPages

        $db_dynamic_pages = new Sitemap;
        $arrPagesDyn = $db_dynamic_pages->getDynamicPages();
        $arrayRuslDynamicPages = $db_dynamic_pages->arrayOfDynamicPages;

        foreach ($arrPagesDyn as $key=>$pageAliases){
            $firstPartOfUrl = Url::home(true);
            for ($i = 0; $i< count($arrayRuslDynamicPages); $i++){
                if ($key == $arrayRuslDynamicPages[$i]['type']){
                    $firstPartOfUrl .= $arrayRuslDynamicPages[$i]['page'];
                }
            }
            foreach ($pageAliases as $pageAlias){
                $alias [] = $firstPartOfUrl . $pageAlias['alias'] . "/";
            }
        }
        return $alias;
    }

    /**
     *
     * Генерирует ссылки из БД для создания SiteMap
     *
     * @param array
     * @param int
     * @param string/null
     *
     * @return array - array url'ы статических страниц
     */
    private function getAliasOnStaticPage($fullArr, $curId, $alias = NULL){

        if ($fullArr[$curId]['id_parent_page'] != 0){

            $alias = $fullArr[$curId]['page_alias'] . "/" . $alias;
            $alias = $this->getAliasOnStaticPage($fullArr, $fullArr[$curId]['id_parent_page'], $alias);
        }
        else{
            $alias = $fullArr[$curId]['page_alias'] . "/"  . $alias;
        }
        return $alias;

    }

    /**
     *
     *
     * @param $idSan int id нужного санатория в Основной таблице
     *
     * @return array первый элемент - array массив основных профилей лечения
     * @return array второй элемент - array массив неосновных профилей лечения
     */
    private function getSanatoriunHealBase($idSan){
        $SQL = 'SELECT `id_profile`, `is_main` FROM `sans_profiles` WHERE `id_san` = :id';
        $profilesHeal = Yii::$app->dbResort->createCommand($SQL)->bindValues([':id' => $idSan] )->queryAll();

        $mainHeal = [];//id основных профилей лечения
        $optionHeal = [];// id неосновных профилей лечения

        foreach ($profilesHeal as $value){
            if ($value['is_main']==1){
                $mainHeal[] = $value['id_profile'];
            }
            else{
                $optionHeal[] = $value['id_profile'];
            }
        }
        unset($profilesHeal);

        $SQL = 'SELECT `name` FROM `profiles` WHERE `id_profile` IN ('. implode(',', $mainHeal) .');  ';
        $healBase[0] = Yii::$app->dbResort->createCommand($SQL)->queryAll();

        $SQL = 'SELECT `name` FROM `profiles` WHERE `id_profile` IN ('. implode(',', $optionHeal) .');  ';
        $healBase[1] = Yii::$app->dbResort->createCommand($SQL)->queryAll();

        //vd($healBase);

        return $healBase;
    }

    public static function CreateSeo(){
        $urlArr = explode('/',Yii::$app->request->pathInfo);//массив родительских страниц
        array_pop($urlArr);//удаление последнего элемента массива, он пуст.
        $okURL  = PagesHelper::getPagesInUrl($urlArr);
        if(!$okURL)
            throw new NotFoundHttpException('Страница не найдена');
        $currentPage  =   $okURL[count($okURL)-1];
        //============ Обработка вызовов виджетов в тексте
        helpers::createSeo($currentPage, $currentPage['page_name'], $currentPage['page_name']);
    }

    /**
     *
     *
     * @param $idSan int id нужного санатория
     *
     * @return array/false подготовленный массив данных о комнатах
     */
    public function getRoomsDataForSanatorium($idSan){
        $SQL = 'SELECT * FROM `rooms` WHERE `id_san` = :id AND `is_active` = 1';
        $rooms = Yii::$app->dbResort->createCommand($SQL)->bindValues([':id' => $idSan, ] )->queryAll();

        $dataRooms = [];
        foreach ($rooms as $value){

            $minPrice = json_decode($value['price_json'], true);

            $price = "Уточняйте у менеджера";
            $type = 0;

            if ($minPrice){
                if (isset($minPrice[4])){
                    $price = $minPrice[4];
                    $type = 4;
                }
                else{
                    $price = $minPrice[5];
                    $type = 5;
                }
            }

            $text = (isset($value['text']) && $value['text']!='') ? $value['text'] : ((isset($value['short_text']) && $value['short_text']!='') ? $value['short_text'] : 'нет описания' );

            $dataRooms [$value['id_room']] = [
                'name'  => $value['name'],
                'text'  => $text,
                'price' => $price.'руб',
                'type'  => $type,
            ];
            unset($type, $price);
        }

        $arrayOnOwnDbRooms = Rooms::find()->where(['id_room_in_main_table' => array_keys($dataRooms)])->asArray()->all();

        if (!empty($arrayOnOwnDbRooms)){
            foreach ($arrayOnOwnDbRooms as $key=>$value){
                $value['name'] = $dataRooms[$value['id_room_in_main_table']]['name'];
                $value['type'] = $dataRooms[$value['id_room_in_main_table']]['type'];
                $value['price'] = $dataRooms[$value['id_room_in_main_table']]['price'];
                unset($dataRooms[$value['id_room_in_main_table']]);
                $arrayOnOwnDbRooms[$key]=$value;
            }
        }

        $roomsReturns[0] = $dataRooms;
        $roomsReturns[1] = $arrayOnOwnDbRooms;

        return $roomsReturns;
    }

    private function getPageInfo(){
        $urlArr = explode('/',Yii::$app->request->pathInfo);//массив родительских страниц

        array_pop($urlArr);//удаление последнего элемента массива, он пуст.

        $okURL  = PagesHelper::getPagesInUrl($urlArr);
        Yii::$app->params['breadcrumbs'] = PagesHelper::generateBreadcrumbs($okURL);

        $pageParam = [];

        foreach ($okURL as $key=>$value){
            if ($value['page_alias'] == $urlArr[0]){
                $pageParam = $okURL[$key];
            }
        }

        if (empty($pageParam)){
            die("Критическая ошибка. Обезьянки уже трудяться.");
        }

        return $pageParam;
    }

    /**
     *
     * @param $dataRooms array массив данных о комнатах, для получения названия комнат
     * @param $type int Тип проживания(4 - Проживание и питание, 5 - Проживание, питание и лечение)
     *
     * @return array - подготовленный массив данных о ценах на комнаты[0] и временные переоды[1] колличество
     */
    /*private function getPricesForSanatorium($dataRooms, $type=5){
        $dataRoomsIds = [];

        if( isset($dataRooms[0])){
            foreach ($dataRooms as $key1=>$value){
                foreach ($value as $key2=>$room){
                    $dataRoomsIds[] = isset($room['id_room_in_main_table']) ? $room['id_room_in_main_table'] : $key2;
                    if (isset($room['id_room_in_main_table'])){
                        $dataRooms[0][$room['id_room_in_main_table']] = $dataRooms[$key1][$key2];
                    }
                }
            }
        }
        else{
            foreach ($dataRooms as $key=>$value){
                $dataRoomsIds[] = $key;
            }
        }

        $dataRooms = $dataRooms[0];

        $SQL = "SELECT `id_room`, `id_when`, `main`, `add`, `alone`
                FROM `room_price` 
                WHERE `id_room` IN (". implode(',', $dataRoomsIds) .") AND `year_start` = 0 AND `year_end` = 0 AND `type` = :type_room";
        $prices = Yii::$app->dbResort->createCommand($SQL)->bindValues([':type_room' => $type])->queryAll();

        //vd($prices);

        $uniquePriceTime = [];

        foreach ($prices as $key=>$price){
            $uniquePriceTime[] = $price['id_when'];
        }

        $uniquePriceTime = array_unique($uniquePriceTime);
        sort($uniquePriceTime);
        $SQL = 'SELECT `start`, `end` FROM `sans_price_time` WHERE `id_time` = :id;';
        for ($i=0; $i<count($uniquePriceTime); $i++){
            $timePrice[$uniquePriceTime[$i]] = Yii::$app->dbResort->createCommand($SQL)->bindValues([':id' => $uniquePriceTime[$i], ] )->queryOne();
        }

        $preparedPrices = [];
        $i = 0;


        foreach ($prices as $price){
            foreach ($timePrice as $key=>$value){
                if ($price['id_when']==$key){
                    $preparedPrices[$i] = array_merge($value, $price);
                }
            }
            $preparedPrices[$i] = array_merge($preparedPrices[$i],['name' => $dataRooms[$price['id_room']]['name']]);
            $i++;
        }

        foreach ($preparedPrices as $key => $value){
            $idRooms[$key] = $value['id_room'];
            $idStart[$key] = $value['start'];
        }

        if (empty($preparedPrices)){
            return false;
        }
        array_multisort($idRooms, SORT_ASC, $idStart, SORT_ASC, $preparedPrices);


        $data[0] = $preparedPrices;
        $data[1] = count($uniquePriceTime);

        return $data;
    }*/

}
