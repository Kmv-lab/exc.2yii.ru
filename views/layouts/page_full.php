<?php

use app\assets\AppAsset;
use app\widgets\Breadcrumbs;

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
    <div class="breadcrumbs-wrapper">
        <div class="container">

            <?=Breadcrumbs::widget([
                 'links' => isset(Yii::$app->params['breadcrumbs']) ? Yii::$app->params['breadcrumbs'] : [],
                'tag'=>'div',
                'itemTemplate'=>'<div>{link}</div>',
                'activeItemTemplate'=>'<div>{link}</div>']) ?>
          <!--  <div class="breadcrumbs">
                <div><a href="/">Главная</a></div>
                <div><a href="/contacts/">Контакты</a></div>
                <div><a href="/style/">Стайл</a></div>
                <div>Не ссылка</div>
            </div>-->
        </div>
    </div>
    <div class="page page-full">
        <div class="container">
            <h1><?=Yii::$app->params['seo_h1']?></h1>
        </div>
        <?= $content ?>
    </div>
<?= $this->render('_Footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
