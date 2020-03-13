<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;

if(isset($stop)){
    echo "<span>НЕТ ДОСТУПА!!!</span>";
}
else{
?>
<div class="report-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //  ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'label'=> 'ID',
            ],
            [
                'attribute' => 'username',
                'label'=> 'Имя',
                'content'=>function($data){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['username'], ['update', 'id'=>$data['id']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
            [
                'attribute' => 'email',
                'label'=> 'E-mail',
            ],
            [   'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
                'buttons'=>[
                    'delete' => function ($url, $data) {
                        return Html::a('Удалить<span class="glyphicon glyphicon-remove"></span>', $url, [
                            'title' => 'Удалить',
                            'class' => 'deleteItem btn btn-default btn-xs'
                        ]);

                    }
                ],
            ],
        ],
    ]); ?>
</div>
<?}
