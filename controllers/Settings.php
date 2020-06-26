<?php
namespace app\controllers;

use Yii;
use yii\base\BootstrapInterface;
use app\commands\PagesHelper;

/*
/* The base class that you use to retrieve the settings from the database
*/

class settings implements BootstrapInterface {

    private $db;

    public function __construct() {
        $this->db = Yii::$app->db;
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * Loads all the settings into the Yii::$app->params array
     * @param Application $app the application currently running
     */

    public function bootstrap($app) {

        $session = Yii::$app->session;

        $session->open();
        // проверяем что сессия уже открыта
        if ($session->isActive) {
            if (!isset($_SESSION['sourse'])){
                $_SESSION['sourse'] = $_SERVER['HTTP_REFERER'];
            }
        }

        // открываем сессию



        // Get settings from database
        $sql = $this->db->createCommand("SELECT set_sys_name, set_sys_value FROM settings_sys ORDER BY id");
        $settings = $sql->queryAll();
        //vd($settings);
        // Now let load the settings into the global params array
        foreach ($settings as $key => $val) {
            Yii::$app->params[$val['set_sys_name']] = $val['set_sys_value'];
        }

        $SQL = 'SELECT `id_page`,  `id_parent_page`,  `page_alias`,  `page_name`,  `page_menu_name`,  `page_breadcrumbs_name`,
       `page_link_title`,  `page_priority`,
       `seo_h1`, `seo_h1_span`,  `seo_description`, `seo_keywords`,  `seo_title`,  `is_active`,  `show_in_menu`,  `show_childs`, no_del, no_change_position,
       show_sitemap, file_name
        FROM pages';
        Yii::$app->params['pages'] = $this->db->createCommand($SQL)->queryAll();
        Yii::$app->params['pages'] = PagesHelper::select(
            Yii::$app->params['pages'],
            array(),
            array(
                array('attr_name'   =>  'is_active',   'operand'=> '=', 'value'=> 1 ),
            ),
            array('attr_name'  => 'page_priority', 'sort_type'=>'ASC')
        );
    }

}
?>
