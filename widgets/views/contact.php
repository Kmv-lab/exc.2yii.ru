<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$count = Yii::$app->params['counter_form'];

if($wrap){ ?>
<div class="el-form-callback">
<?php
}
?>
    <div class="el-header"><?=$name?></div>

    <?php $form = ActiveForm::begin(['action'=>Url::to(['site/contactform']), 'options'=>['class'=>'contact-form']]);
    $model->form_name = $name;?>
    <div class="form-wrapper">
            <?= $form->field($model, "[$count]form_name",  ['template' => '{input}'])->hiddenInput(['class'=>'form-name']) ?>
            <?= $form->field($model, "[$count]name", [
                'template' => '{label}{input}{error}',
                'labelOptions' => ['class' => ''],
                'errorOptions' => ['class' => 'help-block help-block-error '],
                'options' => ['class' => 'el']
            ])->textInput(['class' => '']) ?>
            <?= $form->field($model, "[$count]phone", [
                'template' => '{label}{input}{error}',
                'labelOptions' => ['class' => ''],
                'errorOptions' => ['class' => 'help-block help-block-error '],
                'options' => ['class' => 'el']
            ])->textInput(['class' => 'phonemask']) ?>
            <?= $form->field($model, "[$count]office", [
                'template' => '{label}{input}{error}',
                'labelOptions' => ['class' => ''],
                'errorOptions' => ['class' => 'help-block help-block-error '],
                'options' => ['class' => 'el']
            ])->dropDownList( $model->getCities(),['class' => 'selectBox']) ?>
        <div class="btn-wrapper">
            <?= Html::submitButton($btn_name, ['class' => 'btn']) ?>
        </div>
    </div>
<?php ActiveForm::end();
if($wrap){ ?>
</div>
<?php
}
?>