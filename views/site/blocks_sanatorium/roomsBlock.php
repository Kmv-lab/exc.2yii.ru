<div class="sanatorium-block">
    <div class="sanatorium-rooms">
        <?php

        use app\modules\adm\models\GPhotoSanatoriums;
        use app\widgets\Galleries;

        foreach ($dataRooms as $dataRoomInTable){
            foreach ($dataRoomInTable as $room) {

                ?>

                <div class="sanatorium-room-desc-and-img">
                    <h2 class="titel-room"><?=$room['name']?></h2>
                    <h3 class="min-price-room">
                        <?php

                        switch ($room['type']) {
                            case 4:
                                echo "Цена питания и проживания от :";
                                break;
                            case 5:
                                echo "Цена питания, проживания и лечения от :";
                                break;
                            default:
                                echo "";
                        }
                        ?>
                        <span class="price-value"><?=$room['price']?></span>
                    </h3>
                    <?php



                    if (isset($room['id_gallery'])){
                        //vd($room['id_gallery']);
                        echo Galleries::widget([
                            'id_gal' => $room['id_gallery'],
                            'galleryPhotos' => 'g_photos_sanatoriums',
                            'modelGaleriesPhoto' => new GPhotoSanatoriums()
                        ]);
                    }
                    ?>

                    <div class="sanatorium-room-desc">
                        <span class="desc-room"><?=isset($room['text']) ? $room['text'] : $room['desc'];?></span>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
