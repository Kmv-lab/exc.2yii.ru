<?php
namespace app\widgets;

use yii\base\Widget;
use app\modules\adm\models\Block AS BlockModel;
use app\commands\helpers;


class Block extends Widget
{
    public $id;

    public function run()
    {
        $block = BlockModel::findOne($this->id);

        if(empty($block))
            return '';
        $block->block_content = helpers::search_url($block->block_content);
        return $block->block_content;
    }
}