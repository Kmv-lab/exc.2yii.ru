<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\adm\models\Testi */

?>
<div class="testi-update">

    <div class="testi-form">

        <?php $form = ActiveForm::begin(['id' => 'testi-form',
            'layout' => 'horizontal',
            'options' => [
                // класс формы
                'class' => 'form-horizontal',
                // возможность загрузки файлов
                // 'enctype' => 'multipart/form-data'
            ],]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'data')->textInput(['class' => 'datepicker form-control',
            'value' => $model->data > 0 ? date('d.m.Y',$model->data) : '']) ?>
        <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'is_active',[
            'labelOptions'=>['class'=>'col-sm-3 control-label'],
            'template' => '{label} <div class="col-sm-6 checkbox">{input}{error}{hint}</div>',
        ])->checkbox([],false)?>
        <?= $form->field($model, 'for_main',[
            'labelOptions'=>['class'=>'col-sm-3 control-label'],
            'template' => '{label} <div class="col-sm-6 checkbox">{input}{error}{hint}</div>',
        ])->checkbox([],false)?>

        <div class="form-group">
            <?= Html::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
