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
    <div class="wrapper">
    <?= $this->render('_Header')?>
        <main class="main">
            <div class="page clearfix page_grey">
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
                <?= $content ?>
            </div>
        </main>
    <?= $this->render('_Footer') ?>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
