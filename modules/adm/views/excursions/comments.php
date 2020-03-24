<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$types = $newModel->getTypes();

?>
<div class="timetable-new-block">
    <h1>Создание нового отзыва</h1>
    <?php
    $form = ActiveForm::begin(['id' => 'excursion-form',
        'enableClientValidation'=>false,
        'options' => [
            'class' => 'sanatorium-page new-price-block-small',
        ],
    ]);
    ?>

    <?=$form->field($newModel, 'type')->label('Выбор типа отзыва')->dropDownList($types, ['class' => 'select-type select-type-0']);?>

    <div class="text-comment">
        <div class="timetable-name-icon-time comment-type-0">
            <div class="timetable-name">
                <?=$form->field($newModel, 'name')->label('Имя')->textInput();?>
            </div>
            <div class="timetable-time">
                <?=$form->field($newModel, 'date')->label('Дата отзыва')->input('date');?>
            </div>
            <div class="comment-rating">
                <?=$form->field($newModel, 'rating')->label('Отценка')->textInput(['type' => 'number', 'max' => 5, 'min' => 0, 'step' => 'any']);?>
            </div>
        </div>

        <div class="timetable-content">
            <label for="wysiwyg1">Содержание</label>
            <?=$form->field($newModel, 'content',  [
                'inputOptions' => ['class' => 'ckeditor'],
                'labelOptions' => ['class' => 'col-sm-3 control-label']
            ])->textArea(['id' => 'wysiwyg1'])->label(false);?>
        </div>
    </div>

    <div class="timetable-content-button">

        <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Сохранить', [
            'class' => 'btn btn-lg btn-primary new-price-btn'
        ]);?>

    </div>
    <?
    $form::end();
    ?>
</div>



<div class="timeteble-existing-blocks">
    <?
    if(!empty($comments)){?>
        <h1>Существующие остановки</h1>
        <?
        $i = 1;
        foreach ($comments as $comment){?>
            <div class="timetable-elem-block">

                <?$form2 = ActiveForm::begin(['id' => 'excursion-form',
                    'enableClientValidation'=>false,
                    'options' => [
                        'class' => 'sanatorium-page new-price-block-small',
                    ],
                ]);
                ?>

                <?=$form->field($comment, 'type')->label('Выбор типа отзыва')->dropDownList($types, ['class' => 'select-type select-type-'.$i]);?>
                <?=$form2->field($comment, 'id')->label(false)->hiddenInput();?>
                <?=$form2->field($comment, 'id_exc')->label(false)->hiddenInput();?>


                <div class="text-comment">

                    <?php
                    if ($comment->type == 1){?>
                        <div class="timetable-name-icon-time comment-type-<?=$i?>" style="display: none">
                    <?}
                    else{?>
                        <div class="timetable-name-icon-time comment-type-<?=$i?>">
                    <?}
                    ?>

                        <div class="timetable-name">
                            <?=$form->field($comment, 'name')->label('Имя')->textInput();?>
                        </div>
                        <div class="timetable-time">
                            <?=$form->field($comment, 'date')->label('Дата отзыва')->input('date');?>
                        </div>
                        <div class="comment-rating">
                            <?=$form->field($comment, 'rating')->label('Отценка')->textInput(['type' => 'number', 'max' => 5, 'min' => 0, 'step' => 'any']);?>
                        </div>
                    </div>

                    <div class="timetable-content">
                        <label for="wysiwyg1">Содержание</label>

                        <?php
                        if ($comment->type == 1){?>
                            <?=$form->field($comment, 'content',  [
                                'inputOptions' => ['class' => 'ckeditor'],
                                'labelOptions' => ['class' => 'col-sm-3 control-label']
                            ])->textArea(['id' => 'wysiwyg1', 'value' => 'https://www.youtube.com/watch?v='.$comment->content])->label(false);?>
                        <?}else{?>
                            <?=$form->field($comment, 'content',  [
                                'inputOptions' => ['class' => 'ckeditor'],
                                'labelOptions' => ['class' => 'col-sm-3 control-label']
                            ])->textArea(['id' => 'wysiwyg1'])->label(false);?>
                        <?}?>


                    </div>
                </div>

                <div class="timetable-content-button">

                    <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Сохранить', [
                        'class' => 'btn btn-lg btn-primary new-price-btn'
                    ]);?>
                    <?=Html::a('<span class="glyphicon glyphicon-trash"></span>'.'Удалить', ['delete_commment', 'idComment'=>$comment->id, 'idExc' => $idExc], ['class' => 'btn btn-danger deleteItem button-delete']);?>

                </div>

                <?
                $form2::end();
                ?>

            </div>
        <?}
    }
    ?>


</div>
