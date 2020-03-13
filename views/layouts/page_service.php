<?php

use app\assets\AppAsset;
use app\widgets\BreadcrumbsWidget;

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

<!--содержимое страницы-->
<div class="page-wrapper">
    <div class="container">
        <div class="row">
            <?= BreadcrumbsWidget::widget();?>
            <h1><?= Yii::$app->controller->seo_h1 ?><span><?=Yii::$app->params['seo_h1_span']?></span></h1>
            <div class="page-content">
                <?= $content ?>
            </div><!-- page-content -->

        </div>
    </div>
    <div class="call-action-block">
        <div class="container">
            <div class="row">
                <div class="img"><img src="/img/tetka.png"></div>
                <div class="phone">8 (879-34) 6-000-2, 6-000-3</div>
                <div class="text">Если у вас возникли какие-либо вопросы, то просто позвоните нам по указанному номеру.<br>
                    Квалифицированные менеджеры ответят на все интересующие вас вопросы !</div>
            </div>
        </div>
    </div>
</div>
<!--//содержимое страницы-->


<?= $this->render('_Footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>