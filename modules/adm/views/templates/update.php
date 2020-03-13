<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\adm\models\Template */

?>
<div class="template-update">

    <div class="template-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'tpl_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tpl_content', ['inputOptions' => ['class' => 'codemirror form-control']])->textArea(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
