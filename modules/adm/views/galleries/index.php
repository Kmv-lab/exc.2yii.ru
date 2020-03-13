<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="gallery-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id_gallery',
            [
                'attribute' => 'name',
                'content'=>function($data){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['name'], ['update', 'id'=>$data['id_gallery']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
            [   'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
            ],
        ],
    ]); ?>
</div>
