<?php

use app\widgets\Breadcrumbs;
use app\widgets\Galleries;

?>
<main class="main"><div class="page clearfix page_grey">
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
        <div class="contacts-map">
            <div class="contacts-map__map" id="js-map"></div>
        </div>
        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
        <script>
            ymaps.ready(init);
            var map;
            function init(){
                map = new ymaps.Map("js-map", {
                    center: [44.053415, 42.870597],
                    zoom: 15,
                    controls: []
                });
                map.behaviors.disable('scrollZoom');
                placemark = new ymaps.Placemark(
                    [44.053415, 42.870597],
                    {
                        balloonContent: 'Lorem ipsum'
                    },
                    {
                        preset: 'islands#redDotIcon'
                    }
                );
                map.geoObjects.add(placemark);
            };
        </script>
        <section class="contacts-main">
            <div class="container">
                <div class="contacts-main__wrap">
                    <div class="contacts-main__col">
                        <h2 class="contacts-main__title">Контакты</h2>
                        <ul class="contacts-main__tels">
                            <li>
                                <a href="tel:<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_free'])?>" class="contacts-main__tel"><?=Yii::$app->params['phone_free']?></a>
                            </li>
                            <li>
                                <a href="whatsapp://send?phone=<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_whatsapp'])?>" class="contacts-main__tel"><?=Yii::$app->params['phone_whatsapp']?></a>
                            </li>
                        </ul>
                        <ul class="contacts-main__tels">
                            <li>
                                <a class="contacts-main__mail" href="mailto:<?=Yii::$app->params['adminEmail']?>"><?=Yii::$app->params['adminEmail']?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="contacts-main__col contacts-main__col_long">
                        <h2 class="contacts-main__title">Реквизиты</h2>
                        <div class="contacts-main__txt">
                            <p>Общество с ограниченной ответственностью Туристическая фирма <b>«Туризм»</b></p>
                            <div class="contacts-main__txt-row">
                                <div class="contacts-main__txt-col">
                                    Юридический адрес: <?=Yii::$app->params['mail_index']?>, <?=Yii::$app->params['ur_adress']?>
                                </div>
                                <div class="contacts-main__txt-col">
                                    <div class="contacts-main__txt-col-row">
                                        <div>ОГРН:</div>
                                        <div><?=Yii::$app->params['OGRN']?></div>
                                    </div>
                                    <div class="contacts-main__txt-col-row">
                                        <div>ИНН:</div>
                                        <div><?=Yii::$app->params['INN']?></div>
                                    </div>
                                    <div class="contacts-main__txt-col-row">
                                        <div>КПП:</div>
                                        <div><?=Yii::$app->params['KPP']?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="docs-sec">
            <div class="container">
                <h2 class="sec-subtitle">Сертификаты нашей компании</h2>
                <?=Galleries::widget([
                    'id_gal' => 14
                ]);?>
                <div class="docs-slider-bar">
                    <button class="btn docs-slider-bar__arrow docs-slider-bar__arrow_prev" id="js-docs-slider-bar-prev" title="Назад">Назад</button>
                    <div class="docs-slider-bar__pagination" id="js-docs-slider-bar-paginatio"></div>
                    <button class="btn docs-slider-bar__arrow docs-slider-bar__arrow_next" id="js-docs-slider-bar-next" title="Вперед">Вперед</button>
                </div>
            </div>
        </section>
    </div>
</main>
