<?php

?>

<div class="guides__wrap">
    <?
        foreach ($elems as $elem){
    ?>
    <div class="guides__item">
        <div class="guides__item-pic">
            <img src="<?=$elem->DIRview().Yii::$app->params['resolution_main_excursion_photo'].'/'.$elem->file_name?>" alt="">
        </div>
        <div class="guides__item-name"><?=$elem->name?></div>
        <div class="guides__item-state">
            <?= ($nameOfStaff == 'Guides') ? 'Стаж экскурсовода - ' : ''?>
            <?= ($nameOfStaff == 'Drivers') ? 'Водительски стаж - ' : ''?>
            <?=$elem->expirians?>
        </div>
    </div>
    <?}?>

</div>
