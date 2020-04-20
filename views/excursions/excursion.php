<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<main class="main" xmlns="http://www.w3.org/1999/html"><div class="page clearfix page_grey">
        <nav class="breadcrumbs">
            <div class="container">
                <ul>
                    <li><a href="/"><span>Главная</span></a></li><li><span><?=$excursion->name?></span></li>
                </ul>
            </div>
        </nav>
        <section class="excursion" style="background-image: url(/img/ex-bg.png);">
            <div class="container">
                <h1 class="page-title">Экскурсия <span><?=$excursion->name?></span></h1>
                <div class="excursion__wrap">
                    <div class="excursion__row">
                        <div class="excursion__date">Отправление из г. <?=$excursion->getTowns($excursion->id_town)?></div>
                        <div class="excursion__date">Выезд –
                            <span>
                                <?php
                                $y = 1;
                                $days[$y++] = $price->mon;
                                $days[$y++] = $price->tue;
                                $days[$y++] = $price->wed;
                                $days[$y++] = $price->thu;
                                $days[$y++] = $price->fri;
                                $days[$y++] = $price->sat;
                                $days[$y++] = $price->sun;

                                $daysOfWeek=[];
                                foreach ($days as $key=>$day){
                                    if ($day){
                                        $daysOfWeek[] = $key - 1;
                                    }
                                }

                                $chast_otvechaushaya_za_govnokod = 0;
                                for ($i = 1; $i <= count($price->getDayArray()); $i++){
                                    if ($days[$i]){
                                        if ($chast_otvechaushaya_za_govnokod)
                                            echo ' / ';
                                        echo $price->getDayArray($i);
                                        $chast_otvechaushaya_za_govnokod = 1;
                                    }
                                }
                                ?>

                            </span>
                        </div>
                        <div class="excursion__date">Время выезда – <span><?=ltrim(substr($excursion->time_start, 0, 5), '0')?></span></div>
                        <div class="excursion__date">Время возвращения – <span><?=ltrim(substr($excursion->time_end, 0, 5), '0')?></span></div>
                    </div>
                    <div class="excursion__row">
                        <div class="excursion__col">
                            <div class="excursion__col-banner excursion__col-banner_i">Ближайшая экскурсия состоится
                            <?php
                            $today = new DateTime();//сегодняшняя дата
                            if (in_array($today->format('w'), $daysOfWeek)){
                                echo "сегодня";
                            }
                            elseif (in_array($today->format('w')+1, $daysOfWeek)){
                                echo "завтра";
                            }
                            else{
                                for ($i = $today->format('w')+2; $i<=7; $i++){
                                    if (in_array($today->format('w')-1, $daysOfWeek)){
                                        $nextExc = $today->format('j')." ";
                                        $nextExc .= Yii::$app->params['monhts_to_russian'][$today->format('n')-1];
                                        echo $nextExc;
                                        break;
                                    }
                                    $today = $today->modify('+1 day');
                                }
                                if (!isset($nextExc)){
                                    $today = $today->modify('mon next week');
                                    for ($i = 0; $i<=7; $i++){
                                        if (in_array($today->format('w')-1, $daysOfWeek)){
                                            $nextExc = $today->format('j')." ";
                                            $nextExc .= Yii::$app->params['monhts_to_russian'][$today->format('n')-1];
                                            echo $nextExc;
                                            break;
                                        }
                                        $datetime1 = $today->modify('+1 day');
                                    }
                                }
                            }

                            ?>
                            </div>
                        </div>
                        <div class="excursion__col">
                            <div class="excursion__col-banner excursion__col-banner_travel">Проходимое расстояние в оба конца – <span><?=$excursion->distance?></span>&nbsp;км</div>
                        </div>
                    </div>
                </div>
                <div class="excursion-gallery">
                    <div class="excursion-gallery__main">
                        <?php
                            $count = count($photos);
                            if($count<=8){
                                for ($i=0; $i<$count; $i++){
                                    ?>
                                    <a
                                        href="<?=$photos[$i]->DIRview().'original/'.$photos[$i]->name?>"
                                        style="background-image:
                                            url(<?=$photos[$i]->DIRview().Yii::$app->params['resolution_excursion_photo'].'/'.$photos[$i]->name?>);"
                                        class="js-gallery excursion-gallery__pic"
                                    ></a>
                                <?}?>
                    </div>
                    <div class="excursion-gallery__bar">
                            <?}
                            else{
                                for ($i=0; $i<8; $i++){?>
                                    <a
                                        href="<?=$photos[$i]->DIRview().'original/'.$photos[$i]->name?>"
                                        style="background-image:
                                            url(<?=$photos[$i]->DIRview().Yii::$app->params['resolution_excursion_photo'].'/'.$photos[$i]->name?>);"
                                        class="js-gallery excursion-gallery__pic"
                                    ></a>
                                <?}?>
                        </div>
                        <div class="excursion-gallery__bar">
                            <a href="#" class="btn btn_orange excursion-gallery__more">ЕЩЕ <?=$count-8?> ФОТОГРАФИЙ</a>
                            <?}?>
                        <a data-fancybox href="https://www.youtube.com/embed/<?=$excursion->video_src?>" class="btn btn_orange-brd excursion-gallery__video">СМОТРЕТЬ ВИДЕО</a>
                    </div>
                </div>
                <div class="excursion-roadmap">
                    <h2 class="sec-subtitle sec-subtitle_dark">Расписание экскурсии</h2>
                    <div class="excursion-roadmap__wrap">
                        <?
                        foreach ($timetable as $value){?>
                            <div class="excursion-roadmap-item">
                                <div class="excursion-roadmap-item__header">
                                    <div class="excursion-roadmap-item__time">
                                        <img src="<?=$value->DIR().$value->getIcons($value->icon)?>" alt="">
                                        <span><?=ltrim(substr($value->time, 0, 5), '0')?></span>
                                    </div>
                                    <div class="excursion-roadmap-item__title"><?=$value->name?></div>
                                </div>
                                <div class="excursion-roadmap-item__main">
                                    <p><?=$value->content?></p>
                                </div>
                            </div>
                        <?}?>
                    </div>
                </div>
                <div class="excursion-guide">
                    <h2 class="sec-subtitle sec-subtitle_dark">Экскурсовод маршрута</h2>
                    <div class="excursion-guide__wrap">
                        <div class="guides__item">
                            <div class="guides__item-pic">
                                <img src="<?=$guide->DIRview().Yii::$app->params['resolution_main_excursion_photo'].'/'.$guide->file_name?>" alt="">
                            </div>
                            <div class="guides__item-name"><?=$guide->name?></div>
                            <div class="guides__item-state">Стаж экскурсовода – <?=$guide->expirians?></div>
                        </div>
                        <div class="excursion-guide-cloud">
                            <div class="excursion-guide-cloud__title">Советы экскурсовода</div>
                            <div class="excursion-guide-cloud__txt">Возьмите с собой:</div>
                            <div class="excursion-guide-cloud__row">
                                <?php
                                    foreach ($advices as $advice){?>
                                        <div class="excursion-guide-cloud__item">
                                            <div class="excursion-guide-cloud__item-icn"><img src="<?=$advice->DIR().$advice->getAdvices($advice->id_adv)['file']?>" alt=""></div>
                                            <div class="excursion-guide-cloud__item-txt"><?=$advice->getAdvices($advice->id_adv)['name']?></div>
                                        </div>
                                    <?}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="dop-sec">
            <div class="container">
                <div class="dop-sec__wrap">
                    <h2 class="sec-subtitle">Дополнительные расходы</h2>
                    <ul class="dop-sec__list">
                    <?php
                     foreach ($options as $option){?>
                         <li class="dop-sec__item">
                             <div class="dop-sec__item-icn"><img src="<?=$option->DIR().$option->getOptions($option->id_option)['file']?>" alt=""></div>
                             <div class="dop-sec__item-txt"><?=$option->getOptions($option->id_option)['name']?></div>
                         </li>
                     <?}?>
                    </ul>
                </div>
            </div>
        </section>
        <section class="map-sec">
            <div class="container">
                <h2 class="sec-subtitle sec-subtitle_dark">Карта маршрута</h2>
                <div class="map-sec__pic"><img src="<?=$excursion->DIRview().Yii::$app->params['resolution_main_excursion_photo'].'/'.$excursion->map?>" alt=""></div>
            </div>
        </section>



        <?
        if(!empty($comments)){
        ?>

        <section class="reviews-sec">
            <div class="container">
                <h2 class="sec-subtitle sec-subtitle_dark">Отзывы наших клиентов</h2>
                <div class="reviews-sec-slider" id="js-reviews-sec-slider">

                    <?
                    foreach ($comments as $comment){
                        switch ($comment->type){
                            case 0:?>
                                <div>
                                    <div class="reviews-sec-slide">
                                        <div class="reviews-page-item">
                                            <div class="reviews-page-item__title">Экскурсия <?=$excursion->name?></div>
                                            <div class="reviews-page-item__main">
                                                <div class="reviews-page-item__row">
                                                    <div class="reviews-page-item__col">
                                                        <div class="reviews-page-item__name"><?=$comment->name?></div>
                                                        <time class="reviews-page-item__time" datetime="2019-09-13T08:23:11+03:00"><?=$comment->date?></time>
                                                    </div>

                                                    <div class="rating-of_comment-block">
                                                        <img src="/content/icons/5_hole_of_stars.png" class="rating-of-comment">
                                                        <div style="left: -<?=100-$comment->rating/5*100?>%" class="yellow-back-of-rating"></div>
                                                        <div class="gray-back-of-rating"></div>
                                                    </div>

                                                </div>
                                                <div class="reviews-page-item__txt"><?=$comment->content?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <? break;
                            case 1:?>

                                <div>
                                    <div class="reviews-sec-slide">
                                        <div class="reviews-page-item">
                                            <div class="reviews-page-item__title">Экскурсия <?=$excursion->name?></div>
                                            <a data-fancybox
                                               class="reviews-page-item__video"
                                               href="https://www.youtube.com/embed/<?=$comment->content?>"
                                               style="background-image: url(//img.youtube.com/vi/<?=$comment->content?>/mqdefault.jpg);">
                                            </a>
                                        </div>
                                    </div>
                                </div>

                        <? break;
                        }
                    }

                    ?>
                </div>
                <div class="reviews-slider-bar">
                    <button class="btn reviews-slider-bar__arrow reviews-slider-bar__arrow_prev" id="js-reviews-sec-prev" title="Назад">Назад</button>
                    <div class="reviews-slider-bar__pagination" id="js-reviews-sec-paginatio"></div>
                    <button class="btn reviews-slider-bar__arrow reviews-slider-bar__arrow_next" id="js-reviews-sec-next" title="Вперед">Вперед</button>
                </div>
            </div>
        </section>

        <?}?>

        <section class="booking-sec">

            <div class="container">
                <h2 class="sec-title">Забронировать экскурсию</h2>
                <script>
                    var daysWeek = <?= json_encode($days) ?>;
                </script>
                <?

                $action = Url::to( 'booking/'.$excursion['alias'].'/', true);

                $form = ActiveForm::begin([
                    'action' => $action,
                    'options' => [
                        'class' => 'booking-sec__form',
                    ],
                ]);
                ?>
                    <div class="booking-sec__row">
                        <fieldset class="booking-sec__col">
                            <legend class="booking-sec__col-title">Выберите время и дату</legend>
                            <div class="booking-sec__col-row">
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
                        </fieldset>
                        <fieldset class="booking-sec__col">
                            <legend class="booking-sec__col-title">УКАЖИТЕ количество людей</legend>
                            <div class="booking-sec__col-row">
                                <div class="booking__form-group-adult">
                                    <div class="select-small price-select">
                                        <?
                                        $selects = [
                                            '',
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
                        </fieldset>
                    </div>
                    <div class="booking-sec__footer">
                        <div class="booking-sec__price">
                            <span class="booking-sec__price-title">Итоговая стоимость: </span>
                            <span class="booking-sec__price-val">0</span>
                            <span class="booking-sec__price-rub"> руб.</span>
                        </div>
                        <?=Html::submitButton('Забронировать', [
                            'class' => 'btn btn_orange booking-sec__submit'
                        ]);?>
                    </div>
                <?
                $form::end();
                ?>
            </div>
        </section>


        <section class="exc-sec">
            <div class="container">
                <h2 class="sec-title exc-sec__title">Похожие экскурсии</h2>
                <p class="sec-txt exc-sec__txt">Забронируйте экскурсии и получите электронный билет сразу</p>
                <div class="exc-list">
                    <div class="exc-item">
                        <div class="exc-item__category">Категория</div>
                        <a href="#" class="exc-item__pic">
                            <img src="img/pic/exc-item.jpg" alt="">
                            <div class="exc-item__date">Ближайшее: <b>среда в 14:00</b></div>
                        </a>
                        <div class="exc-item__main">
                            <div class="exc-item__bar">
                                <div class="exc-item__bar-item exc-item__bar-item_rait">Рейтинг: <span>9.1 / 10</span></div>
                                <div class="exc-item__bar-item exc-item__bar-item_time">Длительность: <span>3 часа</span></div>
                            </div>
                            <a href="#" class="exc-item__name">Название экскурсии в две или несколько строк</a>
                            <a href="#" class="exc-item__txt">Равным образом рамки обучения играет важную роль в формировании форм развития. Не следует, однако забывать, что новая модель организационной деятельности влечет за собой процесс.</a>
                        </div>
                        <div class="exc-item__footer">
                            <div class="exc-item__price">от <b>700</b>&nbsp;р.</div>
                            <a href="#" class="btn btn_orange-brd exc-item__btn">ПОДРОБНЕЕ</a>
                        </div>
                    </div>
                    <div class="exc-item">
                        <div class="exc-item__category">Категория</div>
                        <a href="#" class="exc-item__pic">
                            <img src="img/pic/exc-item.jpg" alt="">
                            <div class="exc-item__date">Ближайшее: <b>среда в 14:00</b></div>
                        </a>
                        <div class="exc-item__main">
                            <div class="exc-item__bar">
                                <div class="exc-item__bar-item exc-item__bar-item_rait">Рейтинг: <span>9.1 / 10</span></div>
                                <div class="exc-item__bar-item exc-item__bar-item_time">Длительность: <span>3 часа</span></div>
                            </div>
                            <a href="#" class="exc-item__name">Название экскурсии в две или несколько строк</a>
                            <a href="#" class="exc-item__txt">Равным образом рамки обучения играет важную роль в формировании форм развития. Не следует, однако забывать, что новая модель организационной деятельности влечет за собой процесс.</a>
                        </div>
                        <div class="exc-item__footer">
                            <div class="exc-item__price">от <b>700</b>&nbsp;р.</div>
                            <a href="#" class="btn btn_orange-brd exc-item__btn">ПОДРОБНЕЕ</a>
                        </div>
                    </div>
                    <div class="exc-item">
                        <div class="exc-item__category">Категория</div>
                        <a href="#" class="exc-item__pic">
                            <img src="img/pic/exc-item.jpg" alt="">
                            <div class="exc-item__date">Ближайшее: <b>среда в 14:00</b></div>
                        </a>
                        <div class="exc-item__main">
                            <div class="exc-item__bar">
                                <div class="exc-item__bar-item exc-item__bar-item_rait">Рейтинг: <span>9.1 / 10</span></div>
                                <div class="exc-item__bar-item exc-item__bar-item_time">Длительность: <span>3 часа</span></div>
                            </div>
                            <a href="#" class="exc-item__name">Название экскурсии в две или несколько строк</a>
                            <a href="#" class="exc-item__txt">Равным образом рамки обучения играет важную роль в формировании форм развития. Не следует, однако забывать, что новая модель организационной деятельности влечет за собой процесс.</a>
                        </div>
                        <div class="exc-item__footer">
                            <div class="exc-item__price">от <b>700</b>&nbsp;р.</div>
                            <a href="#" class="btn btn_orange-brd exc-item__btn">ПОДРОБНЕЕ</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>
