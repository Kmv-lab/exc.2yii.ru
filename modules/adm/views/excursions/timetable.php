<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="timetable-new-block">
    <h1>Создание новой остановки</h1>
    <?php
    $form = ActiveForm::begin(['id' => 'excursion-form',
        'enableClientValidation'=>false,
        'options' => [
            'class' => 'sanatorium-page new-price-block-small',
        ],
    ]);
    ?>

    <div class="timetable-name-icon-time">
        <div class="timetable-name">
            <?=$form->field($newModel, 'name')->label('Название/место')->textInput();?>
        </div>
        <div class="timetable-icon">
            <?
                $icons = $newModel->getIcons();
                foreach ($icons as $key=>$icon){
                    $iconsArray[$key] = $icon['name'];
                }
            ?>
            <?=$form->field($newModel, 'icon')->label('Выбор иконки')->dropDownList($iconsArray, ['prompt' => 'Иконка не выбрана!']);?>
        </div>
        <div class="timetable-time">
            <?=$form->field($newModel, 'time')->label('Время')->input('time');?>
        </div>
    </div>

    <div class="timetable-content">
        <label for="wysiwyg1">Содержание</label>
        <?=$form->field($newModel, 'content',  [
            'inputOptions' => ['class' => 'ckeditor'],
            'labelOptions' => ['class' => 'col-sm-3 control-label']
        ])->textArea(['id' => 'wysiwyg1'])->label(false);?>
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
        if(!empty($timetables)){?>
            <h1>Существующие остановки</h1>
            <?
            foreach ($timetables as $timetable){?>
                <div class="timetable-elem-block">

                    <?$form2 = ActiveForm::begin(['id' => 'excursion-form',
                    'enableClientValidation'=>false,
                    'options' => [
                    'class' => 'sanatorium-page new-price-block-small',
                    ],
                    ]);
                    ?>

                    <?=$form2->field($timetable, 'id')->label(false)->hiddenInput();?>
                    <?=$form2->field($timetable, 'id_exc')->label(false)->hiddenInput();?>
                    <div class="timetable-name-icon-time">
                        <div class="timetable-name">
                            <?=$form2->field($timetable, 'name')->label('Название/место')->textInput();?>
                        </div>
                        <div class="timetable-icon">
                            <?
                            $icons = $newModel->getIcons();
                            foreach ($icons as $key=>$icon){
                                $iconsArray[$key] = $icon['name'];
                            }
                            ?>
                            <?=$form2->field($timetable, 'icon')->label('Выбор иконки')->dropDownList($iconsArray, ['prompt' => 'Иконка не выбрана!']);?>
                        </div>
                        <div class="timetable-time">
                            <?=$form2->field($timetable, 'time')->label('Время')->input('time');?>
                        </div>
                    </div>

                    <div class="timetable-content">
                        <label for="wysiwyg1">Содержание</label>
                        <?=$form2->field($timetable, 'content',  [
                            'inputOptions' => ['class' => 'ckeditor'],
                            'labelOptions' => ['class' => 'col-sm-3 control-label']
                        ])->textArea(['id' => 'wysiwyg1'])->label(false);?>
                    </div>

                    <div class="timetable-content-button update-timetable">

                        <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Сохранить', [
                            'class' => 'btn btn-lg btn-primary new-price-btn'
                        ]);?>
                        <?=Html::a('<span class="glyphicon glyphicon-trash"></span>'.'Удалить', ['delete_timetable', 'idTimetable'=>$timetable->id, 'idExc' => $idExc], ['class' => 'btn btn-danger deleteItem button-delete']);?>

                    </div>
                    <?
                    $form2::end();
                    ?>

                </div>
            <?}
        }
    ?>


</div>
