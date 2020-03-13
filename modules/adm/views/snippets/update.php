<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\adm\models\Snippet */

?>
<div class="snippet-update">

    <div class="snippet-form">

        <?php $form = ActiveForm::begin(['enableClientValidation'=>false]); ?>

        <?= $form->field($model, 'snippet_name', ['inputOptions' => ['class' => 'translit_source form-control']])
            ->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'snippet_alias', ['inputOptions' => ['class' => 'translit_dest form-control']])
            ->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'snippet_content', ['inputOptions' => ['class' => 'codemirror form-control']])->textArea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
