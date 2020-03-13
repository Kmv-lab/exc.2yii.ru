<div class="container">

    <?php

    use yii\grid\GridView;
    use yii\helpers\Html;

        echo GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                'id',
                [
                    'attribute' => 'Название',
                    'content'=>function($data){
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['name'], ['update', 'id'=>$data['id']], ['class' => 'btn btn-primary btn-xs']);
                    }
                ],
                [
                    'attribute' => 'Комнаты',
                    'content'=>function($data){
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.'Изменить комнаты', ['rooms', 'id'=>$data['id'], 'sanatoriumName' => $data['name']], ['class' => 'btn btn-primary btn-xs']);
                    }
                ],
            ]
        ]);
    ?>

</div>