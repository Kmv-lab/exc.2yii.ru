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
            </div>
        </div>
    </div>
    <div class="page page-full">
        <div class="container">
            <?php
            if(!empty(Yii::$app->params['photo'])){ ?>
                <div class="top-image">
                    <img src="<?=Yii::$app->params['photo']?>" />
                </div>
            <?php
            }
            ?>
            <h1><?=Yii::$app->params['seo_h1']?><span><?=Yii::$app->params['seo_h1_span']?></span></h1>
            <div class="page-content">
                <?= $content ?>
            </div>
        </div>
    </div>
<?= $this->render('_Footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
