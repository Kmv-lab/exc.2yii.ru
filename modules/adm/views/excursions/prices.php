<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="new-price-block">
    <?php
    $form = ActiveForm::begin(['id' => 'excursion-form',
        'enableClientValidation'=>false,
        'options' => [
            'class' => 'sanatorium-page new-price-block-small',
        ],
    ]);
    ?>
    <h1>Добавить новую цену</h1>
    <div class="dates-price-block">
        <div class="new-price-elem">
            <span>С</span>
            <?=$form->field($model, 'start')->label(false)->input('date');?>
        </div>
        <div class="new-price-elem">
            <span>ПО</span>
            <?=$form->field($model, 'end')->label(false)->input('date');?>
        </div>
    </div>

    <div class="new-price-days-block">
        <div class="labels-for-checkbox">
            <span>Понедельник</span>
            <span>Вторник</span>
            <span>Среда</span>
            <span>Четверг</span>
            <span>Пятница</span>
            <span>Суббота</span>
            <span>Воскресенье</span>
        </div>
        <div class="checkboxes">
            <?= $form->field($model, 'mon')->checkbox()->label(false)?>
            <?= $form->field($model, 'tue')->checkbox()->label(false)?>
            <?= $form->field($model, 'wed')->checkbox()->label(false)?>
            <?= $form->field($model, 'thu')->checkbox()->label(false)?>
            <?= $form->field($model, 'fri')->checkbox()->label(false)?>
            <?= $form->field($model, 'sat')->checkbox()->label(false)?>
            <?= $form->field($model, 'sun')->checkbox()->label(false)?>
        </div>
    </div>

    <div class="new-price-prices-block">
        <div class="price-elem">
            <?=$form->field($model, 'price')->label('Полная цена')->textInput();?>
        </div>
        <div class="price-elem">
            <?=$form->field($model, 'price_ch')->label('Детская цена')->textInput();?>
        </div>
        <div class="price-elem">
            <?=$form->field($model, 'price_pref')->label('Льготная цена')->textInput();?>
        </div>
    </div>

    <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Создать', [
        'class' => 'btn btn-lg btn-primary new-price-btn'
    ]);?>

    <?php
    $form::end();
    ?>



        <? if (!empty($prices)){?>
        <h1>Существующие цены</h1>
        <div class="prices-block existing-price">
            <? foreach ($prices as $price){?>
            <div class="new-price-block">
                    <?php
                    $form2 = ActiveForm::begin(['id' => 'excursion-form',
                        'enableClientValidation'=>false,
                        'options' => [
                            'class' => 'sanatorium-page',
                        ],
                    ]);
                    ?>
                    <?=$form2->field($price, 'id')->label(false)->hiddenInput();?>
                    <?=$form2->field($price, 'id_exc')->label(false)->hiddenInput();?>
                    <div class="dates-price-block">
                        <div class="new-price-elem">
                            <span>С</span>
                            <?=$form2->field($price, 'start')->label(false)->input('date');?>
                        </div>
                        <div class="new-price-elem">
                            <span>ПО</span>
                            <?=$form2->field($price, 'end')->label(false)->input('date');?>
                        </div>
                    </div>

                    <div class="new-price-days-block">
                        <div class="labels-for-checkbox">
                            <span>Понедельник</span>
                            <span>Вторник</span>
                            <span>Среда</span>
                            <span>Четверг</span>
                            <span>Пятница</span>
                            <span>Суббота</span>
                            <span>Воскресенье</span>
                        </div>
                        <div class="checkboxes">
                            <?= $form2->field($price, 'mon')->checkbox()->label(false)?>
                            <?= $form2->field($price, 'tue')->checkbox()->label(false)?>
                            <?= $form2->field($price, 'wed')->checkbox()->label(false)?>
                            <?= $form2->field($price, 'thu')->checkbox()->label(false)?>
                            <?= $form2->field($price, 'fri')->checkbox()->label(false)?>
                            <?= $form2->field($price, 'sat')->checkbox()->label(false)?>
                            <?= $form2->field($price, 'sun')->checkbox()->label(false)?>
                        </div>
                    </div>

                    <div class="new-price-prices-block">
                        <div class="price-elem">
                            <?=$form2->field($price, 'price')->label('Полная цена')->textInput();?>
                        </div>
                        <div class="price-elem">
                            <?=$form2->field($price, 'price_ch')->label('Детская цена')->textInput();?>
                        </div>
                        <div class="price-elem">
                            <?=$form2->field($price, 'price_pref')->label('Льготная цена')->textInput();?>
                        </div>
                    </div>

                    <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Сохранить', [
                        'class' => 'btn btn-lg btn-primary new-price-btn'
                    ]);?>
                    <?=Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['delete_price', 'idPrice'=>$price->id, 'idExc' => $idExc], ['class' => 'btn btn-danger deleteItem']);?>

                    <?php
                    $form2::end();
                    ?>
            </div>

            <?}
        }?>
</div>


</div>
