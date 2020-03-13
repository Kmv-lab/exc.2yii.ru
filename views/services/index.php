
<?php
use yii\helpers\Url;
use app\helpers\d;
?>
<div class="services-page clearfix">

    <?php

    foreach($services AS $service)
    {
        $name = $service['name'];
        $price = 'От '.$service['price_from'].' рублей';
        $file_name = $service['file_name'];
        $url = Url::to(['services/detail', 'alias'=> $service['alias']]);?>
        <div class="item" style="background: url('/content/services/370x228/<?=$file_name?>');">
            <a href="<?php echo $url?>">
                <div class="name"><?=$name?></div>
                <div class="price-from"><?=$price?></div>
            </a>

        </div>
        <?php
    }
    ?>

</div>
<?php
if(!empty($page['page_content'])) echo $page['page_content'];
?>