<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="container">

    <h2>Категории для экскурсии <?=$excursion->name?></h2>

    <?= Html::beginForm(['categoryes', 'idExc' => $idExc], 'post', ['form-name' => 'excCategory']) ?>

    <?

    //vd($excCategory);

    foreach ($categorys as $key => $category){?>

        <div class="box-for-checkbox">
            <?
                echo Html::label($category);
                echo Html::checkbox('category_'.$key, in_array($key, $excCategory) ? true : false);
            ?>
        </div>

    <?}

    ?>

    <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Сохранить', [
        'class' => 'btn btn-lg btn-primary'
    ]);?>

    <?= Html::endForm() ?>

</div>


