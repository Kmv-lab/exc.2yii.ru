<?php

use app\modules\adm\models\Block;
use app\widgets\Breadcrumbs;
use app\widgets\ExcursionsWidget;
use app\widgets\Galleries;

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
                            <div class="exc-sec-filter__col">
                                <div class="datapicker datapicker_small">
                                    <input type="text" name="date" class="js-datapicker" placeholder="Удобная дата" autocomplete="off">
                                </div>
                            </div>
                            <div class="exc-sec-filter__col">
                                <div class="select-small">
                                    <select name="type" class="js-select" data-placeholder="Тип экскурсии">
                                        <option></option>
                                        <option>Тип экскурсии-1</option>
                                        <option>Тип экскурсии-2</option>
                                        <option>Тип экскурсии-3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="exc-sec-filter__col">
                                <div class="select-small">
                                    <select name="type" class="js-select" data-placeholder="Продолжительность">
                                        <option></option>
                                        <option>Продолжительность-1</option>
                                        <option>Продолжительность-2</option>
                                        <option>Продолжительность-3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="exc-sec-filter__col">
                                <button class="btn btn_orange exc-sec-filter__btn">ПОДОБРАТЬ ЭКСКУРСИЮ</button>
                            </div>
                        </div>
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
                'quantityExc' => Yii::$app->params['count_excursion_items_on_excursions']
            ]);
        ?>


            </div>
        </section>


        <section class="callback-sec callback-sec_blue">
            <div class="container">
                <h2 class="sec-subtitle callback-sec__title">Не знаете, какая экскурсия подойдет именно вам?</h2>

                <p class="sec-txt callback-sec__txt">Мы бесплатно подберем тур согласно вашим индивидуальным предпочтениям и состоянию здоровья</p>

                <form action="#" class="callback-form" method="post">
                    <div class="callback-form__row">
                        <input class="input callback-form__input input-name" id="SSSSS" name="name" placeholder="Как к вам обращаться?" type="text" />
                        <input class="input callback-form__input input-phone" name="tel" placeholder="Ваш телефон" required="" type="tel" />
                        <button class="btn btn_orange callback-form__submit" type="submit">Жду звонка</button>
                    </div>
                    <small class="callback-form__sub">*нажимая на кнопку вы даете согласие на обработку своих персональных данных</small></form>
            </div>
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

