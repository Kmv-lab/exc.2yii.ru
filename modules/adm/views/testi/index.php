<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="testi-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'content'=>function($data){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['name'].' '.$data['location'],
                        ['update', 'id'=>$data['id']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
            [
                'attribute' => 'data',
                'content'=>function($data){
                    return date('d.m.Y', $data['data']);
                }
            ],
            [
                'attribute' => 'is_active',
                'content'=>function($data){
                    return $data['is_active'] == 1 ? 'Да' : 'Нет';
                }
            ],
            [   'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
            ],
        ],
    ]); ?>
</div>