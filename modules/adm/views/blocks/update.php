<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\adm\models\Block */

?>
<div class="block-update">

    <div class="block-form">

        <?php $form = ActiveForm::begin([
            'enableClientValidation'=>false,]); ?>

        <?= $form->field($model, 'block_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'block_content',  [
            'inputOptions' => ['class' => $model->ckeditor ? 'ckeditor form-control' : 'codemirror form-control']])->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'ckeditor')->checkbox([],false) ?>

        <div class="form-group">
            <?= Html::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
