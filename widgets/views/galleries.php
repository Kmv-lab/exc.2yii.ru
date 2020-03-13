<?php

$resolution = '200x134/'; ?>


<div class="el-inner-owl owl-carousel ">

    <?php

    $rand = rand ( 0, time());
    foreach ($photos AS $photo){
        echo '<a href="'.$dir.'original/'.$photo['file_name'].'" data-fancybox="gallery'.$rand.'" ><img src="'.$dir.$resolution.$photo['file_name'].'" /></a>';
    }
    ?>
</div>