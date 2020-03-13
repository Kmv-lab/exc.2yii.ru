<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\BlocksWidget;
use app\widgets\ServicesWidget;
use app\widgets\PartnersWidget;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('_Head')?>
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= $this->render('_Header')?>
<div class="main-Image">
    <div class="container">
        <div class="row">
            <h1 class="ubu-bold"><?= Yii::$app->controller->seo_h1; ?></h1>
            <div class="text ubu-light">
<!--                --><?//= BreadcrumbsWidget::widget($url); ?>
                <?php echo BlocksWidget::widget(['id'=>2]); ?>
            </div>
        </div>
        <!--row -->
    </div>
    <!--container -->
</div>

<div class="adv-wrapper ubu clearfix">
    <div class="container">
        <div class="row">
            <div class="item service">Свой сервисный центр</div>
            <div class="item years">30 лет на рынке</div>
            <div class="item warranty">Гарантия качества</div>
            <div class="item clients">1000+ довольных клиентов</div>
        </div>
    </div>
</div>
<?= ServicesWidget::widget(); ?>
<?= PartnersWidget::widget(); ?>
<div class="about-block">
    <div class="container">
        <div class="row">
            <div class="img"><img src="/img/bas-about.jpg" /></div>
            <div class="text">
                    <?= BlocksWidget::widget(['id'=>1]); ?>
                <a href="/about/" class="more">Подробнее</a>
            </div>

        </div>
    </div>
</div>
<?= PartnersWidget::widget(['clients'=>true]); ?>
<?= $this->render('_Footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
