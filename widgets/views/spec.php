<?php

use app\widgets\Pagination;
?>
    <div class="specials">
        <?php
        $DIR = Yii::$app->params['path_to_spec_images'];
        foreach ($spec AS $s){ ?>

            <div class="item">
                <div class="img-wrapper">
                    <?php
                    if(!empty($s['file_name'])){
                        echo '<img src="'.$DIR.'352x194/'.$s['file_name'].'" alt="">';
                    }
                    ?>
                    <span class="country"><?=$s['name']?></span>
                    <span class="dates"><?=date('d.m', $s['date_start'])?> / <?=date('d.m', $s['date_end'])?></span>
                </div>
                <div class="desc"><?=$s['anons']?></div>
                <div class="price">от <span><?=number_format($s['min_price'], 0, '.', '.')?> ₽</span></div>
                <div class="reserve"> <button class="btn" data-modal="true" data-fancybox data-src="#modal-form">Забронировать</button> </div>
            </div>
            <?php
        } ?>
    </div>
<?= Pagination::widget(['count'=>$count, 'pageSize'=>Yii::$app->params['count_spec_items'], 'page'=>$page]);?>

