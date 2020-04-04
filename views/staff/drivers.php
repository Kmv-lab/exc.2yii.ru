<?php

use app\modules\adm\models\Block;
use app\widgets\Staff;

?>
<section class="drivers" style="background-image: url(/img/drivers_bg.png)">
    <div class="container">
        <h1 class="page-title">Ваши безопасные автобусы <br>и&nbsp;опытные водители</h1>
        <p class="drivers__txt">Только комфортные автобусы. Наши автобусы и микроавтобусы проходят техосмотр раз в полгода, а также осматриваются штатный техниками до экскурсии и после возвращения с рейса. Весь транспорт лицензирован в 2019 году и оборудован современной техикой (кондиционерами, видеотехникой, тахографами и системой «Эра-Глонасс»).</p>
        <h2 class="sec-subtitle">У нас самый большой собственный автопарк на&nbsp;Северном&nbsp;Кавказе!</h2>
        <?php $block = Block::find()->where(['id_block' => 24])->one();
        echo $block->block_content;?>
        <h2 class="sec-subtitle">Тысячи километров по серпантину каждый месяц</h2>
        <p class="drivers__txt">В штате работают 7 водителей первой категории со стажем более 20 лет. Каждый водитель проходит обязательно медицинское освидетельствование перед поездкой.</p>
        <?=Staff::widget([
            'nameOfStaff' => 'Drivers'
        ])?>
    </div>
</section>
<?php
    $block = Block::find()->where(['id_block' => 23])->one();
    echo $block->block_content;
?>
<section class="callback-sec callback-sec_blue">
    <div class="container">
        <h2 class="sec-subtitle callback-sec__title">Желаете отправится на экскурсию с нами?</h2>
        <p class="sec-txt callback-sec__txt">Мы бесплатно подберем тур согласно вашим индивидуальным предпочтениям и состоянию здоровья</p>
        <form action="#" method="post" class="callback-form">
            <div class="callback-form__row">
                <input type="text" name="name" class="input callback-form__input" placeholder="Как к вам обращаться?">
                <input type="tel" name="tel" class="input callback-form__input" placeholder="Ваш телефон" required>
                <button class="btn btn_orange callback-form__submit" type="submit">Жду звонка</button>
            </div>
            <small class="callback-form__sub">*нажимая на кнопку вы даете согласие на обработку своих персональных данных</small>
        </form>
    </div>
</section>
<section class="banner">
    <div class="container">
        <h2 class="sec-subtitle banner__title">Средний стаж экскурсоводов – 15 лет</h2>
        <p class="sec-txt banner__txt">Наши экскурсоводы учитывают просьбы и пожелания клиентов во время поездки. По окончанию экскурсии гид прозваниет отдыхающих, чтобы вы не отстали от группы и не опоздали ко времени отбытия автобуса.</p>
        <a href="#" class="btn btn_orange banner__btn">СМОТРЕТЬ о экскурсоводах</a>
    </div>
</section>