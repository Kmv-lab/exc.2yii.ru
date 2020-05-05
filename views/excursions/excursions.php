<?php

use app\modules\adm\models\Block;
use app\modules\adm\models\Excursions;
use app\widgets\Breadcrumbs;
use app\widgets\ExcursionsWidget;
use app\widgets\FormCallManager;
use app\widgets\Galleries;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<main class="main">
    <div class="page clearfix page_grey">
        <nav class="breadcrumbs">
            <div class="container">
                <?=Breadcrumbs::widget([
                    'links' => isset(Yii::$app->params['breadcrumbs']) ? Yii::$app->params['breadcrumbs'] : [],
                    'tag'=>'ul vocab="https://schema.org/" typeof="BreadcrumbList"',
                    'itemTemplate'=>'<li property="itemListElement" typeof="ListItem">{link}</li>',
                    'activeItemTemplate'=>'<li property="itemListElement" typeof="ListItem"><span property="name">{link}</span></li>'
                ]);?>
            </div>
        </nav>

        <section class="excursions">
            <div class="container">
                <h1 class="page-title">Экскурсии из Ессентуков с ценами</h1>

                <?php
                    $block = Block::find()->where(['id_block' => 20])->one();
                    echo $block->block_content;
                ?>

                <?php
                    $block = Block::find()->where(['id_block' => 21])->one();
                    echo $block->block_content;
                ?>
            </div>
        </section>



        <section class="exc-sec exc-sec_white">
            <div class="container">
                <div class="exc-sec-filter">
                    <h2 class="exc-sec-filter__title">Выберите нужные параметры</h2>
                    <div class="exc-sec-filter__wrap">
                        <div class="exc-sec-filter__row">
                            <?
                            $form = ActiveForm::begin([
                                'options' => [
                                    'class' => 'form-filter exc-sec-filter__row',
                                ],
                            ]);
                            ?>
                                <div class="exc-sec-filter__col">
                                    <div class="datapicker datapicker_small">
                                        <?=$form->field($model, 'date')->label(false)->textInput([
                                            'type' => 'text',
                                            'class' => 'js-datapicker',
                                            'placeholder' => 'Дата',
                                            'autocomplete' => 'off'
                                        ]);?>
                                    </div>
                                </div>
                                <div class="exc-sec-filter__col">
                                    <div class="select-small">
                                        <?php

                                        $selects = Excursions::getCategories();

                                        ?>
                                        <?=$form->field($model, 'type')->label(false)->dropDownList($selects, [
                                            'class' => 'js-select',
                                            'data-placeholder' => 'Тип экскурсии'
                                        ]);?>

                                    </div>
                                </div>
                                <div class="exc-sec-filter__col">
                                    <div class="select-small">

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
                                </div>
                                <div style="display: none">
                                    <?=$form->field($model, 'isActive')->label(false)->checkbox();?>
                                </div>
                                <div class="exc-sec-filter__col">
                                    <?=Html::submitButton('ПОДОБРАТЬ ЭКСКУРСИЮ', [
                                        'class' => 'btn btn_orange exc-sec-filter__btn'
                                    ]);?>
                                </div>
                            </div>
                        <?
                        $form::end();
                        ?>
                        <ul class="exc-sec-filter__row">
                            <li class="exc-sec-filter__col">
                                <a href="#" class="exc-sec-filter-item">
                                    <div class="exc-sec-filter-item__pic">
                                        <img src="/content/icons/main-nav_backpack.png" alt="">
                                        <img src="/content/icons/main-nav_backpack_white.png" alt="">
                                    </div>
                                    <div class="exc-sec-filter-item__txt">Индивидуальные</div>
                                </a>
                            </li>
                            <li class="exc-sec-filter__col">
                                <a href="#" class="exc-sec-filter-item">
                                    <div class="exc-sec-filter-item__pic">
                                        <img src="/content/icons/main-nav_jeep.png" alt="">
                                        <img src="/content/icons/main-nav_jeep_white.png" alt="">
                                    </div>
                                    <div class="exc-sec-filter-item__txt">Джиппинг</div>
                                </a>
                            </li>
                            <li class="exc-sec-filter__col">
                                <a href="#" class="exc-sec-filter-item">
                                    <div class="exc-sec-filter-item__pic">
                                        <img src="/content/icons/main-nav_mountains.png" alt="">
                                        <img src="/content/icons/main-nav_mountains_white.png" alt="">
                                    </div>
                                    <div class="exc-sec-filter-item__txt">Подъем в горы</div>
                                </a>
                            </li>
                            <li class="exc-sec-filter__col">
                                <a href="#" class="exc-sec-filter-item">
                                    <div class="exc-sec-filter-item__pic">
                                        <img src="/content/icons/main-nav_directional-sign.png" alt="">
                                        <img src="/content/icons/main-nav_directional-sign_white.png" alt="">
                                    </div>
                                    <div class="exc-sec-filter-item__txt">Лучшие экскурсии</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

        <?php

        echo ExcursionsWidget::widget([
                'quantityExc' => Yii::$app->params['count_excursion_items_on_excursions'],
                'filter' => $model
            ]);
        ?>


            </div>
        </section>


        <section class="callback-sec callback-sec_blue">
            <?=FormCallManager::widget([
                    'h2Text' => Yii::$app->params['form_call_manager_on_excursions']
            ])?>
        </section>

        <section class="docs-sec">
            <div class="container">
                <h2 class="docs-sec__title">Все гиды в нашей команде являются сертифицированными специалистами:</h2>

                <?=Galleries::widget([
                    'id_gal' => 14
                ]);?>

                <div class="docs-slider-bar">
                    <button class="btn docs-slider-bar__arrow docs-slider-bar__arrow_prev" id="js-docs-slider-bar-prev" title="Назад">Назад</button>
                    <div class="docs-slider-bar__pagination" id="js-docs-slider-bar-paginatio">&nbsp;</div>
                    <button class="btn docs-slider-bar__arrow docs-slider-bar__arrow_next" id="js-docs-slider-bar-next" title="Вперед">Вперед</button>
                </div>
            </div>
        </section>

        <?php
            $block = Block::find()->where(['id_block' => 22])->one();
            echo $block->block_content;
        ?>

    </div>
</main>

