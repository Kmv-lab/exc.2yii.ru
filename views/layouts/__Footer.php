<?php
use app\widgets\ServicesWidget;
?>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="sec-menu">
                <div class="col col-1">
                    <div class="top"><a href="/about/">О компании</a></div>
                    <div class="bottom">
                        <ul>
                            <li><a href="/about/for-pertners/">Партнёрам</a></li>
                            <li><a href="/news/">Новости/акции</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col col-2">
                    <div class="top"><a href="/services/">Услуги</a></div>
                    <div class="bottom">
                        <ul>
                            <?= ServicesWidget::widget(['footer_menu' => true]); ?>
                        </ul>
                    </div>
                </div>
                <div class="col col-3">
                    <div class="office office-1">
                        <div class="blue title">Магазин в Ессентуках</div>
                        <div class="text ubu-light"><?=Yii::$app->params['phone_ess']?></div>
                    </div>
                    <div class="office office-2">
                        <div class="title">Магазин в Железноводске</div>
                        <div class="text ubu-light"><?=Yii::$app->params['phone_zhel']?></div>
                    </div>
                    <div class="office office-2">
                        <div class="title">Выезд по Пятигорску</div>
                        <div class="text ubu-light"><?=Yii::$app->params['phone_ptg']?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--container -->
</div>
<!-- footer -->
<div class="copy">
    <div class="container">
        <div class="row">Компьютерный магазин “Бас-Система”© 1997-<?= date('Y')?>. Все права защищены</div>
    </div><!--container -->
</div>