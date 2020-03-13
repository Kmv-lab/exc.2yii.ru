<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="block-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id_block',
            [
                'attribute' => 'block_name',
                'content'=>function($data){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['block_name'],
                        ['update', 'id'=>$data['id_block']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
        ],
    ]); ?>
</div>
