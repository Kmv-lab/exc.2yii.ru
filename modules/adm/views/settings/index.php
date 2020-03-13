<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="settings-update">
    <div class="settings-form">
<?php $form = ActiveForm::begin(['id' => 'settings-form',
            'layout' => 'horizontal',
            'method'=>'post',
            'options' => [
                // класс формы
                'class' => 'form-horizontal',
                // возможность загрузки файлов
                // 'enctype' => 'multipart/form-data'
            ],]);
foreach ($settings AS $setting){ ?>
    <?= $form->field($models[$setting['id']], '['.$setting['id'].']set_sys_value')
        ->textInput(['maxlength' => true])->label($setting['set_sys_desc']) ?>
<?php
}?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>

<?php ActiveForm::end(); ?>
    </div>
</div>
