<?php

use yii\helpers\Url;
use app\widgets\Menu;
?>
<noscript>
    <p class="intro">
        Вам нужно включить JavaScript в своем браузере, чтобы  отображение этой страницы было корректн	<!--You need to enable JavaScript in your browser to display this page correctly.-->
    </p>
</noscript>
<header class="header">
    <div class="header__main">
        <div class="container">
            <div class="header__wrap">
                <div class="header__side">
                    <a href="/" class="header-logo">
                        <img src="<?=Yii::$app->params['path_to_official_images']?>logo.png" alt="Экскурсии из Ессентуков">
                    </a>
                    <div class="header-slogan">
                        <?=Yii::$app->params['slogan_main']?>
                    </div>
                </div>
                <div class="header__side header-contacts">
                    <div class="header-contact">
                        <div class="header-contact__title">Звонок бесплатный:</div>
                        <a href="tel:<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_free'])?>" class="header-contact__tel"><?=Yii::$app->params['phone_free']?></a>
                    </div>
                    <div class="header-contact">
                        <div class="header-contact__title">Номер для WhatsApp:</div>
                        <a href="whatsapp://send?phone=<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_whatsapp'])?>" class="header-contact__tel"><?=Yii::$app->params['phone_whatsapp']?></a>
                    </div>
                </div>
                <button class="btn mob-menu-btn js-mob-menu-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </div>
    <nav class="header-nav" id="js-mob-menu">
        <button class="btn mob-menu__close js-mob-menu-btn"></button>
        <div class="container">
            <? echo Menu::widget(); ?>
            <div class="header-contact">
                <div class="header-contact__title">Звонок бесплатный:</div>
                <a href="tel:<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_free'])?>" class="header-contact__tel"><?=Yii::$app->params['phone_free']?></a>
            </div>
            <div class="header-contact">
                <div class="header-contact__title">Номер для WhatsApp:</div>
                <a href="whatsapp://send?phone=<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_whatsapp'])?>" class="header-contact__tel"><?=Yii::$app->params['phone_whatsapp']?></a>
            </div>
        </div>
    </nav>
</header>




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
