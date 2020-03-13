<?php

use app\modules\adm\models\GPhotoSanatoriums;
use app\widgets\Galleries;


/*vd('g_photos_sanatoriums', false);
vd($model);*/

if (isset($idGallery) && $idGallery != ''){
    $model = new GPhotoSanatoriums();?>

    <div class="sanatorium-block">
        <?=Galleries::widget([
            'id_gal' => $idGallery1,
            'galleryPhotos' => 'g_photos_sanatoriums',
            'modelGaleriesPhoto' => $model
        ]);?>
    </div>

<?}