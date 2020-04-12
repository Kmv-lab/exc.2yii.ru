<?php

use app\widgets\Block;
use app\widgets\Breadcrumbs;
use app\widgets\FormCallManager;
use app\widgets\Staff;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$urlToGuides = Url::to( 'ekskursovodyi', true);

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

        <h1 class="page-title"><?=$paramsPage['seo_h1']?></h1>

        <section class="drivers" style="background-image: url(/img/drivers_bg.png)">
            <div class="container">

                <p class="drivers__txt">Только комфортные автобусы. Наши автобусы и микроавтобусы проходят техосмотр раз в полгода, а также осматриваются штатный техниками до экскурсии и после возвращения с рейса. Весь транспорт лицензирован в 2019 году и оборудован современной техикой (кондиционерами, видеотехникой, тахографами и системой «Эра-Глонасс»).</p>
                <h2 class="sec-subtitle">У нас самый большой собственный автопарк на&nbsp;Северном&nbsp;Кавказе!</h2>
                <?= Block::widget([
                    'id' => 24
                ]);?>
                <h2 class="sec-subtitle">Тысячи километров по серпантину каждый месяц</h2>
                <p class="drivers__txt">В штате работают 7 водителей первой категории со стажем более 20 лет. Каждый водитель проходит обязательно медицинское освидетельствование перед поездкой.</p>
                <?=Staff::widget([
                    'nameOfStaff' => 'Drivers'
                ])?>
            </div>
        </section>
        <?= Block::widget([
            'id' => 23
        ]);?>
        <section class="callback-sec callback-sec_blue">
            <?=FormCallManager::widget([
                'h2Text' => Yii::$app->params['form_call_manager_on_drivers']
            ])?>
        </section>
        <section class="banner">
            <div class="container">
                <h2 class="sec-subtitle banner__title">Средний стаж экскурсоводов – 15 лет</h2>
                <p class="sec-txt banner__txt">Наши экскурсоводы учитывают просьбы и пожелания клиентов во время поездки. По окончанию экскурсии гид прозваниет отдыхающих, чтобы вы не отстали от группы и не опоздали ко времени отбытия автобуса.</p>
                <a href="<?=$urlToGuides?>" class="btn btn_orange banner__btn">СМОТРЕТЬ о экскурсоводах</a>
            </div>
        </section>
    </div>
</main>