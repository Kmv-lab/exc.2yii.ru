<?php

use app\widgets\Pagination;
?>
<div class="actions">
    <?php
    $DIR = Yii::$app->params['path_to_actions_images'];
    foreach ($actions AS $action){ ?>
        <div class="item">
            <?php
            if(!empty($action['file_name'])){ ?>
                <div class="img-wrapper"><img alt="<?=$action['name']?>" src="<?=$DIR.'360x200/'.$action['file_name']?>" /></div>
            <?php
            }
            ?>

            <div class="name"><?=$action['name']?></div>

            <div class="anons"><?=$action['anons']?></div>
        </div>
        <?php
    } ?>
</div>
<?= Pagination::widget(['count'=>$count, 'pageSize'=>Yii::$app->params['count_actions_items'], 'page'=>$page]);?>