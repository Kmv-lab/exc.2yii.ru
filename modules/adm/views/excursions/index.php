<?php

use app\modules\adm\models\Excursions;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$guides = [
    1 => 'Алексеев Никита Хрушёвич',
    2 => 'Николаев Игорь Игорьевич',
    3 => 'Брюзга Василий Петрович',
    4 => 'Дуркин Дурак Дуракович'
];

$towns = [
    1 => 'Ессентуки',
    2 => 'Пятигорск',
    3 => 'Железноводск'
];

?>

<div class="container">

    <div class="btn-success new-elem-exc">Создать новую экскурсию</div>
    <div class="new-elem-craete" style="display: none">

        <?
        $form = ActiveForm::begin([
            'options' => [
                'class' => 'sanatorium-page form-horizontal',
            ],
        ]);
        ?>

            <?=$form->field($model, 'name')->label('Название')->textInput();?>

            <span style="font-weight: bold">Описание</span>
            <?=$form->field($model, 'desc',  [
                                                'inputOptions' => ['class' => 'ckeditor'],
                                                'labelOptions' => ['class' => 'col-sm-3 control-label']
                                            ])->textArea(['id' => 'wysiwyg1'])->label(false);?>

            <?=$form->field($model, 'main_photo')->fileInput([
                'multiple' => false,
                'id' => "main_photo",
            ])->label('Главная картинка санатория');?>

            <?=$form->field($model, 'video_src')->label('Ссылка на видео(YouTube.com)')->textInput()?>

            <!--Выбор гида-->
            <?=$form->field($model, 'id_guide')->label('Выбор экскурсовода')->dropDownList($guides, ['prompt' => 'Экскурсовод не выбран!']);?>

            <?=$form->field($model, 'map')->fileInput([
                'multiple' => false,
                'id' => "map",
            ])->label('Картинка карты экскурсии');?>

            <?=$form->field($model, 'distance')->label('Расстояние маршрута экскурсии')->textInput()?>

            <!--Изменить на звёздочки, возможно, а может и нет-->
            <?=$form->field($model, 'rating')->label('Рейтинг')->textInput()?>

            <!--Выбор города-->
            <?=$form->field($model, 'id_town')->label('Выбор города')->dropDownList($towns, ['prompt' => 'Город не выбран!']);?>

            <?=$form->field($model, 'duration')->label('Продолжительность')->textInput()?>

            <?=$form->field($model, 'time_start')->label('Начало экскурсии(отправление)')->input('time');?>
            <?=$form->field($model, 'time_end')->label('Конец экскурсии(Приезд)')->input('time');?>

            <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Сохранить', [
                                        'class' => 'btn btn-lg btn-primary'
                                    ]);?>

            <?
        $form::end();
        ?>
    </div>

    <?php

    echo GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            'id',
            [
                'attribute' => 'Название',
                'content'=>function($data){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> '.$data['name'], ['update', 'idExc'=>$data['id']], ['class' => 'btn btn-primary btn-xs']);
                }
            ],
            [
                'attribute' => 'Удалить',
                'content'=>function($data){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['delete', 'idExc'=>$data['id']], ['class' => 'btn btn-danger deleteItem']);
                }
            ],
        ]
    ]);

    ?>

</div>


