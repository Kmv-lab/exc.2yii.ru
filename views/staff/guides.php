<?php

use app\widgets\Block;
use app\widgets\Breadcrumbs;
use app\widgets\FormCallManager;
use app\widgets\Galleries;
use app\widgets\Staff;
use yii\helpers\Url;

$urlTo = Url::to( 'voditeli', true);

?>

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

        <h1 class="page-title SEO-h1 guides"><?=$paramsPage['seo_h1']?></h1>

        <section class="guides">
            <div class="container">

                <h2 class="sec-subtitle">Средний стаж экскурсоводов &ndash; 15 лет</h2>

                <p class="guides__txt">Наши экскурсоводы учитывают просьбы и пожелания клиентов во время поездки. По окончанию экскурсии гид прозваниет отдыхающих, чтобы вы не отстали от группы и не опоздали ко времени отбытия автобуса.</p>
                <?=Staff::widget([
                    'nameOfStaff' => 'Guides'
                ])?>
            </div>
        </section>

        <section class="docs-sec">
            <div class="container">
                <h2 class="docs-sec__title">Все гиды в нашей команде являются сертифицированными специалистами:</h2>

                <div>
                    <?=(Galleries::widget(['id_gal' => 14]));?>
                </div>
            </div>
        </section>
        <?=Block::widget([
            'id' => 22
        ]);?>
        <section class="callback-sec callback-sec_blue">
            <?=FormCallManager::widget([
                'h2Text' => Yii::$app->params['form_call_manager_on_guides']
            ])?>
        </section>

        <section class="banner">
            <div class="container">
                <h2 class="sec-subtitle banner__title">Только комфортные и безопасные автобусы</h2>
                <p class="sec-txt banner__txt">Наши автобусы и микроавтобусы проходят техосмотр раз в полгода, а также осматриваются штатными техниками до экскурсии и после возвращения с рейса. Весь транспорт лицензиорован в 2019 году.</p>
                <a class="btn btn_orange banner__btn" href="<?=$urlTo?>">СМОТРЕТЬ ТРАНСПОРТ</a></div>
        </section>
    </div>
</main>
