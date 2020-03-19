<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

    <div class="container">

        <button class="btn-success new-elem-exc">Добавить нового экскурсовода</button>
        <div class="new-elem-craete" style="display: none">

        <?php

        $form = ActiveForm::begin([
            'options' => [
                'class' => 'sanatorium-page form-horizontal',
            ],
        ]);
        ?>

            <?=$form->field($model, 'name')->label('ФИО Экскурсовода')->textInput()?>

            <?=$form->field($model, 'expirians')->label('Опыт работы экскурсовода')->textInput()?>

            <?=$form->field($model, 'file_name')->fileInput([
                'multiple' => false,
                'id' => "file_name",
            ])->label('Фотография Экскурсовода');?>

            <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Сохранить', [
                'class' => 'btn btn-lg btn-primary'
            ]);?>

        </div>

        <?php

        $form::end();

        echo GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                'id',
                [
                    'attribute' => 'Имя',
                    'content'=>function($data){
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['name'], ['update', 'idGuide'=>$data['id']], ['class' => 'btn btn-primary btn-xs']);
                    }
                ],
                [
                    'attribute' => 'Удалить',
                    'content'=>function($data){
                        return Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['delete', 'idGuide'=>$data['id']], ['class' => 'btn btn-danger deleteItem']);
                    }
                ],
            ]
        ]);

        ?>

    </div>

