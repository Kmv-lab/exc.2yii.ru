<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Slider;
use app\widgets\Block;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('_Head') ?>
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<?= $this->render('_Header')?>

<?
/*echo Slider::widget(['id'=>1]);
echo Block::widget(['id'=>15]);
echo Block::widget(['id'=>16]);
echo Block::widget(['id'=>17]);
echo Block::widget(['id'=>18]);*/
?>

<?=$content?>

<?= $this->render('_Footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
