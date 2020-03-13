<?php

use yii\helpers\Html;
use app\widgets\Treeview;

?>
<div class="page-index">
    <?= Treeview::widget(['tree'=>$resolutionsArr, 'id'=>'resolution-treeview']); ?>
</div>