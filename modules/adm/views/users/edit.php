<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="row">
    <div class="error_container col-12 col-sm-12 col-md-7 col-lg-6 col-xl-5"><?php echo Html::errorSummary($model,['class'=>'alert alert-dismissable alert-warning']); ?></div>
</div>
<div class="row">
    <?php
    $form = ActiveForm::begin(['id' => 'user-form',
        'layout' => 'horizontal',
        'options' => [
            // класс формы
            'class' => 'form-horizontal',
            // возможность загрузки файлов
            // 'enctype' => 'multipart/form-data'
        ],]);
    ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'username'); //->label(false)?>
    <?= $form->field($model, 'password') ?>
    <div class="form-group">
        <div class="col-lg-offset-8 col-lg-2 col-sm-offset-8 col-sm-2">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success',]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>