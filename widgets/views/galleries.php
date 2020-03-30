<?php

$resolution = '200x134/'; ?>


<div class="docs-slider" id="js-doc-slider">

    <?php

    $rand = rand ( 0, time());
    foreach ($photos AS $photo){
        echo '<div>
                <a href="'.$dir.'original/'.$photo['file_name'].'" data-fancybox="gallery'.$rand.'" class="docs-slider__item js-gallery" >
                    <img src="'.$dir.$resolution.$photo['file_name'].'" />
                </a>
              </div>';
    }
    ?>
</div>
