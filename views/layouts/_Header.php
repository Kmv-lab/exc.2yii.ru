<?php

use yii\helpers\Url;
use app\widgets\Menu;
?>


<div class="container-fluid">
    <div class="header center-content row header-color">
        <div class="col-xs-2 hidden-lg">
            <img src="<?=Yii::$app->params['path_to_official_images']?>menu-open.png" id="showMenu" class="menu-open-img">
        </div>
        <div class="col-xs-10 col-lg-6 align-middle title-name-block center-content">
            <span class="title-name">Санатории Пятигорска</span>
        </div>
        <div class="visible-lg-not-important row col-lg-6 nav-container" id="mainMenu">
            <div style="display: flex; height: 100%; flex-direction: column; justify-content: space-evenly;">
                <!--<ul class="nav-menu">
                    <?php
/*                    $arrMenu = [
                        [
                            'name' => 'Главная',
                            'url' => '/'
                        ],
                        [
                            'name' => 'Цены',
                            'url' => '/prices'
                        ],
                        [
                            'name' => 'Что-то',
                            'url' => '/'
                        ]
                    ];
                    foreach ($arrMenu as $elem){*/?>
                        <li class="menu-elem"><a href="<?/*=$elem['url']*/?>"><?/*=$elem['name']*/?></a></li>
                    <?/*}
                    */?>
                </ul>-->
                <? echo Menu::widget(); ?>
                <div class="phone-block center-content hidden-lg">
                    <button class="btn btn-success call-back-button">Заказать звонок</button>
                </div>
            </div>
        </div>
    </div>

    <?php

        //vd(Yii::$app->params);

    ?>

    <div class="phone-block header-color center-content">
        <span class="phone"><a href="tel:+1234567890">8 (800) 009-08-02</a></span>
        <button class="btn btn-success call-back-button">Заказать звонок</button>
    </div>





    <!--<div class="wrapper-for-mobile">
        <div class="header">
            <div class="container">
                <div class="offices">
                    <div class="office f-300">
                        <div class="item">
                            <div class="name f-400">ООО ТК «Ладья»</div>
                            <div class="city f-500">Пятигорск:</div>
                            <div class="adres"><?/*=Yii::$app->params['address_1']*/?></div>
                        </div>
                        <div class="tel">
                            <div class="regular">
                                <a href="tel:<?/*=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_1_1'])*/?>">
                                    <?/*=Yii::$app->params['phone_1_1']*/?>
                                </a>
                            </div>
                            <div class="cell">
                                <a href="whatsapp:<?/*=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_1_2'])*/?>">
                                    <?/*=Yii::$app->params['phone_1_2']*/?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="office f-300">
                        <div class="item">
                            <div class="name f-400">ИП Тимошенко В.Н.</div>
                            <div class="city f-500">Кисловодск:</div>
                            <div class="adres"><?/*=Yii::$app->params['address_2']*/?></div>
                        </div>
                        <div class="tel">
                            <div class="regular">
                                <a href="tel:<?/*=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_2_1'])*/?>">
                                    <?/*=Yii::$app->params['phone_2_1']*/?>
                                </a>
                            </div>
                            <div class="cell">
                                <a href="whatsapp:<?/*=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_2_2'])*/?>">
                                    <?/*=Yii::$app->params['phone_2_2']*/?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="links">
                    <button href="#" class="btn" data-modal="true" data-fancybox="" data-src="#modal-form">Подобрать тур</button>
                    <a href="#" class="show-contacts">Контакты</a>
                    <a href="<?/*=Url::to(['site/contacts'])*/?>" class="contacts f-300">Все контакты</a>
                </div>
            </div>
        </div>
        <? //echo Menu::widget(); ?>
    </div>-->
