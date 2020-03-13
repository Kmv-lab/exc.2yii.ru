<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="gallery-index">
    <?
        echo Html::a('<span class="glyphicon glyphicon-pencil"></span> Добавить Галлерею для санатория', ['new_gallery', 'id' => $idSan, 'name' => $sanName], ['class' => 'btn btn-primary btn-xs']);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'content'=>function($data, $idGal) use ($sanName){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['name'], ['gallery_update', 'id' => $idGal, 'name' => $sanName], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
            [
                'attribute' => 'Удалить',
                'content'=>function($data) use ($sanName, $idSan){
                    //vd($sanName);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> Удалить', ['delete_galery', 'idSan' => $idSan, 'name' => $sanName, 'idGallery' => $data['id']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
        ],
    ]);

    unset(Yii::$app->params['sanName'], Yii::$app->params['sanName']);

    ?>
</div>
