<?php

use app\modules\adm\models\Excursions;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<section class="main-form" style="background-image: url(img/main-form_bg.jpg)">
    <div class="container">
        <div class="main-form__wrap">
            <div class="main-form__content">
                <h2 class="sec-title main-form__title">Подберите экскурсию на свой вкус</h2>
                <p class="main-form__txt">
                    <strong>Более 6 лет</strong> мы делаем Ваш отдых удобным, а впечатления незабываемыми. В нашем каталоге 25 популярных экскурсий по Кавказу: святые места, шопингтуры городские прогулки для взрослых и детей, пооды в горы и многое другое. Каждая экскурсия <strong>лично посещена нашими менеджерами</strong>.
                </p>
            </div>
            <?
            $form = ActiveForm::begin([
                    'action' => 'ekskursii/',
                'options' => [
                    'class' => 'form main-form__form',
                ],
            ]);
            ?>
                <div class="datapicker">
                    <?=$form->field($model, 'date')->label(false)->textInput([
                        'type' => 'text',
                        'class' => 'js-datapicker',
                        'placeholder' => 'Дата',
                        'autocomplete' => 'off'
                    ]);?>
                </div>
                <div class="select">
                    <?php
                    $selects = Excursions::getCategories();
                    ?>
                    <?=$form->field($model, 'type')->label(false)->dropDownList($selects, [
                        'class' => 'js-select',
                        'data-placeholder' => 'Тип экскурсии'
                    ]);?>
                </div>
                <div class="select">
                    <?php

                    $selects_duration = [
                        'Длительность',
                        'менее 3 часов',
                        'менее 6 часов',
                        'более 6 часов'
                    ];

                    ?>
                    <?=$form->field($model, 'duration')
                        ->label(false)->dropDownList($selects_duration, [
                            'class' => 'js-select',
                            'data-placeholder' => 'Продолжительность'
                        ]);?>
                </div>
                <div style="display: none">
                    <?=$form->field($model, 'isActive')->label(false)->checkbox();?>
                </div>
                <?=Html::submitButton('ПОДОБРАТЬ ЭКСКУРСИЮ', [
                    'class' => 'btn btn_orange exc-sec-filter__btn'
                ]);?>
            <?
            $form::end();
            ?>
        </div>
    </div>
</section>
