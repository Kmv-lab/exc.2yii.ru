<?php

use app\widgets\SanatoriumsPrices;

?>

<div class="full-content content">
    <div class="sanatorium-title center-content">
        <h1 class="sanatorium-title">
            <?=$sanatorium->name?>
        </h1>
    </div>
    <?php

    foreach ($modelSanBlock as $sanBlock){

        switch ($sanBlock['type']){
            case 0:
                //Профили лечения
                echo $this->render('/site/blocks_sanatorium/healProfileBlock', [
                    'mainHeal' => $mainHeal,
                    'optionHeal' => $optionHeal
                ]);
                break;
            case 1:
                //galleries
                echo $this->render('/site/blocks_sanatorium/galleryBlock', [
                    'idGallery' => $sanBlock->content
                ]);
                break;
            case 2:
                //wysiwyg
                echo $this->render('/site/blocks_sanatorium/wysiwygBlock', [
                    'content' => $sanBlock->content
                ]);
                break;
            case 3:
                //code mirror
                echo $this->render('/site/blocks_sanatorium/codeMirrorBlock', [
                    'content' => $sanBlock->content
                ]);
                break;
            case 4:
                //youtube video
                echo $this->render('/site/blocks_sanatorium/videoYoutubeBlock', [
                    'video' => $sanBlock->content
                ]);
                break;
            case 5:
                //номера
                echo $this->render('/site/blocks_sanatorium/roomsBlock', [
                    'dataRooms' => $dataRooms
                ]);
                break;
            case 6:
                //цены
                echo SanatoriumsPrices::widget([
                        'idSan' => $sanatorium->id,
                        'isDropDown' => false
                ]);
        }
    }

    ?>

</div>