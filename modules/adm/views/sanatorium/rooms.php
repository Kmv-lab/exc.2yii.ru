<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>
<div class="container">
<h1 class="text-center">Страница редактирования Комнат для <?=$sanatoriumName?></h1>

<?php
Pjax::begin(['timeout' => 3000, 'enablePushState' => false]);
$i = 0;
    foreach ($rooms as $id=>$room){
        echo Html::beginForm('', 'post', ['data-pjax' => '1', 'class' => 'form-inline']);
    ?>
        <div class="green elem-adm-block">
            <h2 class="name-room-block"><?=$room['name']?></h2>
            <div class="block-adm-to-edit old-desc-block">
                <h3>Старое описание</h3>
                <div class="old-desc"><?=$room['text']?></div>
            </div>
            <?=Html::textarea('desc', '', ['class' => 'ckeditor', 'id' => 'wysiwyg'.$i]);?>
            <div class="block-adm-to-edit flex-box-adm">
                <div>
                    <?
                    echo Html::a('Добавить галерею', Url::to(['sanatorium/new_gallery', "id" => $sanatoryId, 'name' => $sanatoriumName]), ['class' => 'btn btn-primary']);
                    if (!empty($gallery)) {
                        echo Html::dropDownList('gallery', '', $gallery, ['prompt' => 'Галерея не выбрана!']);
                    }
                    ?>
                </div>
                <?=Html::hiddenInput('id_room_in_main_table', $id)?>
                <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span>'.'Сохранить', ['class' => 'btn btn-lg btn-primary']);?>
            </div>
        </div>
    <?php
        echo Html::endForm();
        $i++;
    }

    foreach ($modelRooms as $room){
        echo Html::beginForm('', 'post', ['data-pjax' => '1', 'class' => 'form-inline']);
        ?>
        <div class="green elem-adm-block">
            <h2 class="name-room-block"><?=$room['name']?></h2>
            <?=Html::textarea('new-desc', $room['desc'], ['class' => 'ckeditor', 'id' => 'wysiwyg'.$i]);?>
            <div class="block-adm-to-edit flex-box-adm">
                <div>
                    <?php
                    echo Html::a('Добавить галерею', Url::to(['sanatorium/new_gallery', "id" => $sanatoryId, 'name' => $sanatoriumName]), ['class' => 'btn btn-primary']);
                    if (!empty($gallery)){
                        echo Html::dropDownList('new-gallery', $room['id_gallery'], $gallery, ['prompt' => 'Галерея не выбрана!']);
                    }
                    ?>

                </div>
                <?=Html::hiddenInput('new-id_room_in_main_table', $room['id_room_in_main_table'])?>
                <?=Html::hiddenInput('new-id', $room['id'])?>
                <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span>'.'Обновить', ['class' => 'btn btn-lg btn-primary']);?>
            </div>
        </div>
        <?php
        echo Html::endForm();
        $i++;
    }
Pjax::end();
    ?>
</div>

