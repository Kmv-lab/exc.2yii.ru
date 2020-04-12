<?php

use app\widgets\Block;
use app\widgets\Breadcrumbs;
use app\widgets\FormCallManager;

?>
<main class="main"><div class="page clearfix page_grey">
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
        <section class="faq">
            <div class="container">
                <h1 class="page-title">Впревые путешествуете по кавказу?</h1>
                <h2 class="sec-subtitle">Отвечаем на самые популярные вопросы</h2>
                <?=Block::widget([
                    'id' => 25
                ]);?>

            </div>
        </section>
        <section class="callback-sec callback-sec_blue">
            <?=FormCallManager::widget([
                'h2Text' => Yii::$app->params['form_call_manager_on_faq']
            ])?>
        </section>
    </div>
</main>