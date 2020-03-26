<?php

use app\modules\adm\models\Excursions;

?>

<div class="exc-item" id="exc-item-<?=$number?>">
    <div class="exc-item__category">Категория</div>
    <?php
    if ($exc['is_hit']){
    ?>
    <div class="exc-item__hit">ХИТ</div>
    <?}?>
    <a href="#" class="exc-item__pic">
        <img src="<?=Excursions::DIRview().Yii::$app->params['resolution_main_excursion_photo'].'/'.$exc['main_photo']?>" alt="">
        <div class="exc-item__date">Ближайшее: <b><?=$exc['next_day']?> в <?=$exc['time_start']?></b></div>
    </a>
    <div class="exc-item__main">
        <div class="exc-item__bar">
            <div class="exc-item__bar-item exc-item__bar-item_rait">Рейтинг: <span><?=$exc['rating']?> / 10</span></div>
            <div class="exc-item__bar-item exc-item__bar-item_time">Длительность: <span><?=$exc['duration']?></span></div>
        </div>
        <a href="#" class="exc-item__name"><?=$exc['name']?></a>
        <span class="exc-item__txt"><?=$exc['desc']?></span>
    </div>
    <div class="exc-item__footer">
        <div class="exc-item__price">от <b><?=$exc['prise']?></b>&nbsp;р.</div>
        <a href="#" class="btn btn_orange-brd exc-item__btn">ПОДРОБНЕЕ</a>
    </div>
</div>
