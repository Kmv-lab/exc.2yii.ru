<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
<div class="spec-update">
    <div class="spec-form">

        <?php $form = ActiveForm::begin(['id' => 'spec-form',
            'layout' => 'horizontal',
            'options' => [
                // класс формы
                'class' => 'form-horizontal',
                // возможность загрузки файлов
                // 'enctype' => 'multipart/form-data'
            ],]); ?>

        <?= $form->field($model, 'name', ['inputOptions' => ['class' => 'form-control']])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'min_price', ['inputOptions' => ['class' => 'form-control']])->textInput(['maxlength' => true, 'type'=>'number']) ?>

        <?= $form->field($model, 'date_publication')->textInput(['class' => 'datepicker form-control',
            'value' => $model->date_publication > 0 ? date('d.m.Y',$model->date_publication) : '']) ?>

        <div class="form-group">
            <?= $form->field($model, 'date_start', [
                'template' => '{label}<div class="col-sm-3">{input}</div>{error}',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'errorOptions' => ['class' => 'col-sm-1 help-block help-block-error'],
                'options' => ['tag'=>false]
            ])->textInput(['class' => 'datepicker form-control',
                'value' => $model->date_start > 0 ? date('d.m.Y',$model->date_start) : '']) ?>

            <?= $form->field($model, 'date_end', [
                'template' => '{label}<div class="col-sm-3">{input}</div>{error}',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'errorOptions' => ['class' => 'col-sm-1 help-block help-block-error'],
                'options' => ['tag'=>false]
            ])->textInput(['class' => 'datepicker form-control',
                'value' => $model->date_end > 0 ? date('d.m.Y',$model->date_end) : '']) ?>

        </div>

        <?= $form->field($model, 'file_name')->fileInput(['multiple' => false]);?>
        <?php
        if(!empty($model->file_name)) {

            $iconDel = '<span class="glyphicon glyphicon-remove"></span>';
            $iconEdit = '<span class="glyphicon glyphicon-pencil"></span>';
            $iconOK = '<span class="glyphicon glyphicon-ok"></span>';

            $this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends' => ['yii\web\JqueryAsset']]);
            $this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');
            $i = 1;
            $delUrl = URL::to(['delete_photo', 'id' => $model->id]);
            $resetUrl = URL::to(['reset_photo', 'id' => $model->id]);
            $count_str = ceil(count($resolutions) / 3); ?>
            <div class="form-group image_thumb">
                <div class="col-sm-12">
                    <div class="col-sm-9">
                        <?php for ($y = 1; $y <= $count_str; $y++) { ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <?php for ($x = 1; $x <= 3; $x++) {
                                        $key_resolution = (($y - 1) * 3) + $x - 1;
                                        if (!empty($resolutions[$key_resolution])){
                                            $resolution = $resolutions[$key_resolution];  ?>
                                            <div class="col-sm-4">
                                                <?php echo Html::img($model->DIRview() . $resolution . '/' . $model->file_name . '?' . rand(1, 10000), ['class' => "img-thumbnail img-responsive", 'data-ratio' => $resolution]); ?>
                                                <p><?php echo $resolution ?> </p>
                                            </div>
                                            <?php
                                        }
                                    } ?>

                                </div>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="col-sm-3" style="float: right;padding-top: 20px;">
                        <div class="form-group">
                            <select class="form-control input-sm" name="Photo_ratio_<?= $model->id ?>"
                                    id="Photo_ratio_<?= $model->id ?>">
                                <option value="0" selected="selected">Все разрешения</option>
                                <?php foreach ($resolutions AS $resolution) { ?>
                                    <option value="<?php echo $resolution ?>"><?php echo $resolution ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group"><a href="#" class="btn btn-primary btn-xs show-dialog-thumb"
                                                   data-id="<?= $model->id ?>" data-file_name="<?= $model->file_name ?>">Редактировать
                                миниатюру <?= $iconEdit ?></a></div>
                        <div class="form-group"><a href="<?= $resetUrl ?>"
                                                   class="btn btn-primary btn-default btn-xs reset-thumb">Сбросить
                                миниатюры <?= $iconEdit ?></a></div>
                        <div class="form-group">
                            <a href="<?= $delUrl ?>"
                               class="col-sm-5 deleteItem btn btn-inverse btn-default btn-xs">Удалить <?= $iconDel ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?= $form->field($model, 'anons', [
            'template' => '<div class="form-group">{label}</div>
            <div class="form-group">
                <div class="col-lg-12">
                    {input}{error}
                </div>
            </div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label']
        ])->textArea(['maxlength' => true]) ?>


        <?= $form->field($model, 'is_active',[
            'labelOptions'=>['class'=>'col-sm-3 control-label'],
            'template' => '{label} <div class="col-sm-6 checkbox">{input}{error}{hint}</div>',
        ])->checkbox([],false)?>


        <div class="form-group">
            <?= Html::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
<div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=\app\modules\adm\models\Spec::DIRview().'original/'?>"
     data-url="<?=URL::to(['spec/ajaxcreatethumb'])?>" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Заголовок модального окна -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Детали</h4>
            </div>
            <!-- Основное содержимое модального окна -->
            <div class="modal-body">
                Пока пусто
            </div>
            <!-- Футер модального окна -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                <a href="#" id="thumb-ready"  class="btn btn-primary btn-sm">Готово</a>
            </div>
        </div>
    </div>
</div>
