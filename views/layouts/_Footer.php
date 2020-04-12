<?php

use app\widgets\Block;
use app\widgets\Contact;
use app\widgets\Menu;

?>

<footer class="footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-main__wrap">
                <div class="footer-main__col">
                    <a href="/" class="footer-logo">
                        <img src="<?=Yii::$app->params['path_to_official_images']?>logo.png" alt="Экскурсии из Ессентуков">
                    </a>
                    <div>
                        <div class="footer__title">Мы в соц. сетях:</div>

                        <?

                        $socialArr['vk'] = isset(Yii::$app->params['vk']) ? Yii::$app->params['vk'] : false;
                        $socialArr['ok'] = isset(Yii::$app->params['ok']) ? Yii::$app->params['ok'] : false;
                        $socialArr['insta'] = isset(Yii::$app->params['insta']) ? Yii::$app->params['insta'] : false;
                        $socialArr['fb'] = isset(Yii::$app->params['fb']) ? Yii::$app->params['fb'] : false;

                        ?>
                        <ul class="footer-soc">
                            <?php
                                foreach ($socialArr as $name => $url){
                                    if ($url){?>
                                        <li><a href="<?=$url?>" target="_blank" style="background-image: url(<?='/content/icons/'.$name.'.png'?>); background-size: cover;" title="<?=$name?>"></a></li>
                                    <?}
                                }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="footer-main__col">
                    <nav class="footer-nav">
                        <div class="footer__title">Навигация:</div>
                        <? echo Menu::widget([
                                'footer' => true
                        ]); ?>
                    </nav>
                </div>
                <div class="footer-main__col">
                    <div>
                        <div class="footer__title">Наш адрес:</div>
                        <p><?=Yii::$app->params['address']?></p>
                    </div>
                    <div>
                        <div class="footer__title">Наш E-mail:</div>
                        <a href="mailto:<?=Yii::$app->params['adminEmail']?>"><?=Yii::$app->params['adminEmail']?></a>
                    </div>
                </div>
                <div class="footer-main__col">
                    <div>
                        <div class="footer__title">Наши телефоны:</div>
                        <p>Прямой в Ессентуках</p>
                        <a href="tel:<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone'])?>" class="footer-tel"><?=Yii::$app->params['phone']?></a>
                    </div>
                    <div>
                        <p>Бесплатный по России</p>
                        <a href="tel:<?=preg_replace('/[^0-9\+]/', '', Yii::$app->params['phone_free'])?>" class="footer-tel"><?=Yii::$app->params['phone_free']?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <div class="copyright">© Copyrights 2019. Все права защищены</div>
        </div>
    </div>
</footer>

   <!-- <div class="footer">
        <div class="container">
            <div class="menu-bottom">
                <?/*= Block::widget(['id'=>14]); */?>
            </div>
            <div class="left">
                <div class="img-wrapper">
                    <img src="/img/logo-footer.jpg" />
                </div>
            </div>
            <div class="middle office">
                <div class="city">Пятигорск:</div>
                <div class="adres"><?/*=Yii::$app->params['address_1']*/?></div>
                <div class="phones">
                    <?/*=Yii::$app->params['phone_1_1']*/?><br>
                    <?/*=Yii::$app->params['phone_1_2']*/?><br>
                    <?/*=Yii::$app->params['phone_1_3']*/?><br>
                </div>
                <div class="social-title">Мы в социальных сетях: </div>
                <div class="social">
                    <a href="#"><img src="/img/insta.jpg" /></a>
                    <a href="#"><img src="/img/insta.jpg" /></a>
                </div>
            </div>
            <div class="right office">
                <div class="city">Кисловодск:</div>
                <div class="adres"><?/*=Yii::$app->params['address_2']*/?></div>
                <div class="phones">
                    <?/*=Yii::$app->params['phone_2_1']*/?><br>
                    <?/*=Yii::$app->params['phone_2_2']*/?><br>
                    <?/*=Yii::$app->params['phone_2_3']*/?><br>
                </div>
                <div class="social-title">Мы в социальных сетях: </div>
                <div class="social">
                    <a href="#"><img src="/img/insta.jpg" /></a>
                    <a href="#"><img src="/img/insta.jpg" /></a>
                </div>

            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="text">Copyright © 2005-2019 </div>
            <div class="link">Сайт разработан <a href="https://kmv-lab.ru" target="_blank"><img src="/img/lab-logo.png" alt=""></a></div>
        </div>
    </div>

<?/*= Block::widget(['id'=>19]); */?>

<div id="modal-form">
    <button data-fancybox-close type="button" name="button" class="btn-close"></button>
    <?/*= Contact::widget(['name'=>'Закажите обратный звонок', 'wrap'=>false, 'btn_name'=>'Жду звонка']);*/?>
</div>-->