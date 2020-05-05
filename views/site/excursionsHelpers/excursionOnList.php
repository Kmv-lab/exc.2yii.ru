<?php

use app\modules\adm\models\Excursions;
use yii\helpers\Html;
use yii\helpers\Url;

$urlTo = Url::to( 'excursion/'.$exc['alias'], true);

?>

<div class="exc-item" id="exc-item-<?=$number?>">
    <?

        $sql = 'SELECT `id_category` FROM `exc_category` WHERE `id_exc` = '.$exc['id_exc'];

        $categoryes = Yii::$app->db->createCommand($sql)->queryAll();

        $marginTop = 0;
        foreach ($categoryes as $category){
            if($marginTop){
                echo "<div style=\"top: ".$marginTop."px\" class=\"exc-item__category\">".Excursions::getCategories($category['id_category'])."</div>";
            }
            else{
                $marginTop += 10;
                echo "<div style=\"top: 10px\" class=\"exc-item__category\">".Excursions::getCategories($category['id_category'])."</div>";
            }
            $marginTop += 50;
        }

    ?>
    <?php
    if ($exc['is_hit']){
    ?>
    <div class="exc-item__hit">ХИТ</div>
    <?}?>
    <a href="<?=$urlTo?>" class="exc-item__pic">
        <img src="<?=Excursions::DIRview().Yii::$app->params['resolution_main_excursion_photo'].'/'.$exc['main_photo']?>" alt="">

        <?php

            if(!$filterDate){?>
                <div class="exc-item__date">Ближайшее: <b><?=$exc['next_day']?> в <?=ltrim(substr($exc['time_start'], 0, 5), '0')?></b></div>
            <?}
            else{
                $dateExc = new DateTime($exc['next_day']);
                $viewsDate = $dateExc->format('d')." ";
                $viewsDate .= Yii::$app->params['monhts_to_russian'][$dateExc->format('m') - 1]." ";

                ?>
                <div class="exc-item__date"><b><?=$viewsDate?> в <?=ltrim(substr($exc['time_start'], 0, 5), '0')?></b></div>
            <?}
        ?>


    </a>
    <div class="exc-item__main">
        <div class="exc-item__bar">
            <div class="exc-item__bar-item exc-item__bar-item_rait">Рейтинг: <span><?=$exc['rating']?> / 10</span></div>
            <div class="exc-item__bar-item exc-item__bar-item_time">Длительность: <span><?=$exc['duration']?> ч.</span></div>
        </div>
        <?=Html::a($exc['name'], $urlTo, ['class' => 'exc-item__name']) ?>
        <span class="exc-item__txt"><?=$exc['desc']?></span>
    </div>
    <div class="exc-item__footer">
        <div class="exc-item__price">от <b><?=$exc['price']?></b>&nbsp;р.</div>
        <?=Html::a('ПОДРОБНЕЕ', $urlTo, ['class' => 'btn btn_orange-brd exc-item__btn']) ?>
    </div>
</div>
