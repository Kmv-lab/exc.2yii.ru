<?php

use app\modules\adm\models\Excursions;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<main class="main">
    <div class="page clearfix page_grey">
        <?php

        $y = 1;
        $days[$y++] = $price->mon;
        $days[$y++] = $price->tue;
        $days[$y++] = $price->wed;
        $days[$y++] = $price->thu;
        $days[$y++] = $price->fri;
        $days[$y++] = $price->sat;
        $days[$y++] = $price->sun;

        $form = ActiveForm::begin([
            'options' => [
                'class' => 'booking-sec__form',
            ],
        ]);
        ?>
        <script>
            var daysWeek = <?= json_encode($days) ?>;
        </script>
        <nav class="breadcrumbs">
            <div class="container">
                <ul>
                    <li><a href="/"><span>Главная</span></a></li><li><span>Бронирование</span></li>
                </ul>
            </div>
        </nav>
        <section class="booking">
            <div class="container">
                <h1 class="page-title">Бронирование экскурсии</h1>
                <div class="booking__wrap clearfix">
                    <div class="booking__pic">
                        <img src="<?=Excursions::DIRview().Yii::$app->params['resolution_main_excursion_photo'].'/'.$excursion->main_photo?>" alt="">
                    </div>
                    <div class="booking__form">
                        <div class="booking__form-group">
                            <div class="booking__form-group-title">Название экскурсии</div>
                            <div class="booking__form-group-header"><?=$excursion->name?></div>
                        </div>
                        <div class="booking__form-group">
                            <div class="booking__form-group-title">Выберите время и дату</div>
                            <div class="booking__form-group-row">
                                <!--<div class="booking__form-group-time">
                                    <div class="select-small">
                                        <select name="type" class="js-select" data-placeholder="Время">
                                            <option></option>
                                            <option>9:00</option>
                                            <option>12.00</option>
                                            <option>14:00</option>
                                        </select>
                                    </div>
                                </div>-->
                                <div class="booking__form-group-date">
                                    <div class="datapicker datapicker_small">
                                        <?=$form->field($model, 'date')->label(false)->textInput([
                                            'type' => 'text',
                                            'class' => 'js-datapicker',
                                            'placeholder' => 'Дата',
                                            'autocomplete' => 'off'
                                        ]);?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="booking__form-group">
                            <div class="booking__form-group-title">УКАЖИТЕ количество людей</div>
                            <div class="booking__form-group-row">
                                <div class="booking__form-group-adult">
                                    <div class="select-small price-select">
                                        <?
                                        $selects = [
                                            'Лабе',
                                            1,
                                            2,
                                            3,
                                            4,
                                            5,
                                            6
                                        ];
                                        ?>
                                        <?=$form->field($model, 'price')->label(false)->dropDownList($selects, [
                                            'class' => 'js-select',
                                            'id' => 'select',
                                            'priceValue' => $price->price,
                                            'data-placeholder' => 'Взрослых'
                                        ]);?>
                                    </div>
                                </div>
                                <?php
                                if($price->price_ch!==null){?>
                                    <div class="booking__form-group-children">
                                        <div class="select-small price-select">
                                            <?=$form->field($model, 'price_ch')->label(false)->dropDownList($selects, [
                                                'class' => 'js-select',
                                                'priceValue' => $price->price_ch,
                                                'data-placeholder' => 'Детей'
                                            ]);?>
                                        </div>
                                    </div>
                                <?}?>
                                <?php
                                if($price->price_pref!==null){?>
                                    <div class="booking__form-group-benefits">
                                        <div class="select-small price-select">
                                            <?=$form->field($model, 'price_pref')->label(false)->dropDownList($selects, [
                                                'class' => 'js-select',
                                                'priceValue' => $price->price_pref,
                                                'data-placeholder' => 'Льготных'
                                            ]);?>
                                        </div>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                        <div class="booking__form-group">
                            <div class="booking__form-group-title">Итоговая стоимость</div>
                            <div class="booking__form-group-total">
                                <span class="booking-sec__price-val">0</span>
                                руб.
                            </div>
                        </div>
                    </div>
                    <div class="booking__price">
                        <div class="booking__price-title">ЦЕНЫ НА ЭКСКУРСИЮ</div>
                        <div class="booking__price-row">
                            <div class="booking__price-col">
                                <div class="booking__price-col-title">За взрослого:</div>
                                <div class="booking__price-col-val"><?=$price->price?> руб.</div>
                            </div>

                            <?if ($price->price_ch){?>
                            <div class="booking__price-col">
                                <div class="booking__price-col-title">За детей:</div>
                                <div class="booking__price-col-val"><?=$price->price_ch?> руб.</div>
                            </div>
                            <?}?>

                            <?if ($price->price_pref){?>
                            <div class="booking__price-col">
                                <div class="booking__price-col-title">За льготника:</div>
                                <div class="booking__price-col-val"><?=$price->price_pref?> руб.</div>
                            </div>
                            <?}?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="callback-sec callback-sec_blue">
            <div class="container">
                <form action="#" method="post" class="callback-form">
                    <div class="callback-form__row">

                        <?=$form->field($model, 'personName')->label(false)->textInput([
                            'type' => 'text',
                            'class' => 'input callback-form__input',
                            'placeholder' => 'Как к вам обращаться?'
                        ]);?>

                        <?=$form->field($model, 'personPhone')->label(false)->textInput([
                            'type' => 'tel',
                            'class' => 'input callback-form__input callback-form-phone',
                            'placeholder' => 'Ваш телефон'
                        ]);?>

                        <?=$form->field($model, 'personEmail')->label(false)->textInput([
                            'type' => 'email',
                            'class' => 'input callback-form__input',
                            'placeholder' => 'Ваш Email'
                        ]);?>

                    </div>
                    <div class="callback-form__btns-row">
                        <div class="callback-form__btns-row-col">
                            <div class="callback-form__btns-row-title">Готовы забронировать?</div>
                            <?=Html::submitButton('ОПЛАТИТЬ ОНЛАЙН', [
                                'class' => 'btn btn_orange callback-form__btns-row-btn'
                            ]);?>
                        </div>
                        <div class="callback-form__btns-row-col">
                            <div class="callback-form__btns-row-title">Остались вопросы?</div>
                            <button class="btn btn_orange callback-form__btns-row-btn" type="button">ЖДУ ЗВОНКА</button>
                        </div>
                    </div>

                    <small class="callback-form__sub">Каждый клиент гарантированно получит оплаченный им билет.<br>Так же можете ознакомиться с <a href="#" target="_blank">гарантией возврата средств</a>.</small>
                </form>
            </div>
        </section>
        <?
        $form::end();
        ?>
    </div>
</main>