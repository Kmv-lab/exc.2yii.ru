<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;

class Snippets extends Widget
{
    public $alias;
    public function init()
    {
        // этот метод будет вызван методом CController::beginWidget()
    }

    public function run()
    {
        $SQL      = 'SELECT * FROM snippets WHERE snippet_alias = :alias';
        $snippet  = Yii::$app->db->createCommand($SQL)->bindValue(':alias', $this->alias)->queryOne();

        if (!empty($snippet))
            echo $snippet['snippet_content'];
    }
}
?>