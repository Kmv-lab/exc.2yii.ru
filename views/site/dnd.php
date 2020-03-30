<?php


?>
<main class="main">

    <?php
    if (isset($blocks) && !empty($blocks)){
        foreach ($blocks as $block){
            echo $block->content;
        }
    }
    ?>

</main>
