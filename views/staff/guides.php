<?php

use app\modules\adm\models\Block;
use app\widgets\Galleries;
use app\widgets\Staff;

?>

<section class="guides">
    <div class="container">
        <h1 class="page-title">Информация о наших гидах</h1>

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
            <?=Galleries::widget([
                'id_gal' => 14
            ]);?>
        </div>
    </div>
</section>

    <?=Block::widget([
        'id_gal' => 14
    ]);?>

<section class="callback-sec callback-sec_blue">
    <div class="container">
        <h2 class="sec-subtitle callback-sec__title">Желаете послушать интересные авторские программы наших&nbsp;экскурсоводов?</h2>

        <p class="sec-txt callback-sec__txt">Мы бесплатно подберем тур согласно вашим индивидуальным предпочтениям и состоянию здоровья</p>

        <form action="#" class="callback-form" method="post">
            <div class="callback-form__row"><input class="input callback-form__input" name="name" placeholder="Как к вам обращаться?" type="text" /> <input class="input callback-form__input" name="tel" placeholder="Ваш телефон" required="" type="tel" /><button class="btn btn_orange callback-form__submit" type="submit">Жду звонка</button></div>
            <small class="callback-form__sub">*нажимая на кнопку вы даете согласие на обработку своих персональных данных</small></form>
    </div>
</section>

<section class="banner">
    <div class="container">
        <h2 class="sec-subtitle banner__title">Только комфортные и безопасные автобусы</h2>

        <p class="sec-txt banner__txt">Наши автобусы и микроавтобусы проходят техосмотр раз в полгода, а также осматриваются штатными техниками до экскурсии и после возвращения с рейса. Весь транспорт лицензиорован в 2019 году.</p>
        <a class="btn btn_orange banner__btn" href="#">СМОТРЕТЬ ТРАНСПОРТ</a></div>
</section>
