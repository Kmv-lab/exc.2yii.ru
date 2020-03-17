<?php

use app\modules\adm\models\Excursions;
use app\modules\adm\models\Guides;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends'=>['yii\web\JqueryAsset']]);
$this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');

?>

<div class="container">

    <div class="new-elem-craete">

        <?
        $form = ActiveForm::begin(['id' => 'excursion-form',
            'enableClientValidation'=>false,
            'options' => [
                'class' => 'sanatorium-page form-horizontal',
            ],
        ]);
        ?>

        <?=$form->field($model, 'name')->label('ФИО Экскурсовода')->textInput()?>

        <?=$form->field($model, 'expirians')->label('Опыт работы экскурсовода')->textInput()?>

        <?=$form->field($model, 'file_name')->fileInput([
            'multiple' => false,
            'id' => "file_name",
        ])->label('Фотография Экскурсовода');?>

        <?php

        if (is_file(Guides::DIR().'original/'.$model['file_name'])){?>

            <div class="form-group image_thumb">
                <div class="col-sm-12">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <?=Html::img(Excursions::DIRview().Yii::$app->params['resolution_main_excursion_photo'].'/'.$model['file_name'], ['class'=> "img-thumbnail img-responsive",'data-ratio'=> Yii::$app->params['resolution_main_excursion_photo']]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3" style="float: right;padding-top: 20px;">
                        <div class="form-group">
                            <select style="display: none" class="is_main_sanatorium_photo form-control input-sm" name="Photo_ratio_<?=$model->id?>" id="Photo_ratio_<?=$model->id?>">
                                <option value="<?=Yii::$app->params['resolution_main_excursion_photo']?>" selected="selected">Все разрешения</option>
                                <option value="<?=Yii::$app->params['resolution_main_excursion_photo']?>">...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <a href="#" class="btn btn-primary btn-xs show-dialog-thumb" data-id="<?=$model->id?>" data-name="file_name" data-file_name="<?=$model['file_name']?>" >
                                Редактировать миниатюру <?='<span class="glyphicon glyphicon-pencil"></span>';?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <?}?>

        <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.'Сохранить', [
            'class' => 'btn btn-lg btn-primary'
        ]);?>

        <div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=Excursions::DIRview().'original/'?>"
             data-url="<?=URL::to(['guides/ajaxcreatethumb', 'idGuide' => $model->id, 'name' => 'file_name'])?>" class="modal fade">
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

        <?
        $form::end();
        ?>
    </div>
</div>
