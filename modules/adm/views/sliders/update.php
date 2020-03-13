<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\adm\models\Slider */

?>
<div class="slider-update">

    <div class="slider-form">

        <?php $form = ActiveForm::begin(['id' => 'slider-form',
            'layout' => 'horizontal',
            'options' => [
                // класс формы
                'class' => 'form-horizontal',
                // возможность загрузки файлов
                'enctype' => 'multipart/form-data'
            ],]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'is_active', [
            'labelOptions'=>['class'=>'col-sm-3 control-label'],
            'template' => '{label} <div class="col-sm-6 checkbox">{input}{error}{hint}</div>',
        ])->checkbox([],false) ?>
<?php
        if(!$model->getIsNewRecord()){
            echo $form->field($model, 'files_name[]')->fileInput(['multiple' => true]);
            if(!empty($photos))
                echo '<h4>Уже загруженные фотографии</h4>';
            $i=0;
            $iconDel    = '<span class="glyphicon glyphicon-remove"></span>';
            $iconEdit   = '<span class="glyphicon glyphicon-pencil"></span>';
            $iconOK     = '<span class="glyphicon glyphicon-ok"></span>';
            $template = '{label}<div>{input}{error}</div>';
            $this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends'=>['yii\web\JqueryAsset']]);
            $this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');

            foreach ($photos AS $key=>$photo){
                $id = $photo->id_photo;
                $i++;
                $delUrl = URL::to(['delete_photo', 'id'=>$id]);
                $resetUrl = URL::to(['reset_photo', 'id'=>$id]);
                $count_str =  ceil(count($resolutions)/3); ?>
                <div class="form-group image_thumb">
                    <div class="col-sm-12">
                        <div class="col-sm-9">
                            <?php   for($i = 1; $i <= $count_str; $i++){   ?>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php           for($x = 1; $x <= 3; $x++){
                                            $key_resolution = (($i-1)*3) + $x - 1;
                                            if(!empty($resolutions[$key_resolution])) {
                                                $resolution = $resolutions[$key_resolution]; ?>
                                                <div class="col-sm-4">
                                                    <?php echo Html::img($photo->DIRview().$resolution.'/'.$photo->file_name
                                                        .'?'.rand(1,10000),
                                                        ['class'=> "img-thumbnail img-responsive",'data-ratio'=>$resolution]); ?>
                                                    <p><?php echo $resolution ?> </p>
                                                </div>
                                                <?php
                                            }
                                        } ?>

                                    </div>
                                </div>
                            <?php   }   ?>

                        </div>
                        <div class="col-sm-3" style="float: right;padding-top: 20px;">
                            <?= $form->field($photo, '['.$key.']priority', [
                                'template' => $template,
                                'inputOptions' => ['class' => 'form-control'],
                                'labelOptions' => ['class' => 'control-label']])->textInput(['maxlength' => true]); ?>
                            <?=  $form->field($photo, '['.$key.']alt', [
                                'template' => $template,
                                'inputOptions' => ['class' => 'form-control'],
                                'labelOptions' => ['class' => 'control-label']])->textInput(['maxlength' => true]); ?>
                            <div class="form-group">
                                <select class="form-control input-sm" name="Photo_ratio_<?=$id?>" id="Photo_ratio_<?=$id?>">
                                    <option value="0" selected="selected">Все разрешения</option>
                                    <?php    foreach($resolutions AS $resolution){ ?>
                                        <option value="<?php echo $resolution ?>"><?php echo $resolution ?></option>
                                    <?php    }?>
                                </select>
                            </div>
                            <div class="form-group"><a href="#" class="btn btn-primary btn-xs show-dialog-thumb" data-id="<?=$id?>" data-file_name="<?=$photo->file_name?>" >Редактировать миниатюру <?=$iconEdit?></a></div>
                            <div class="form-group"><a href="<?=$resetUrl?>" class="btn btn-primary btn-default btn-xs reset-thumb">Сбросить миниатюры <?=$iconEdit?></a></div>
                            <div class="form-group">
                                <a href="<?=$delUrl?>" class="col-sm-5 deleteItem btn btn-inverse btn-default btn-xs">Удалить <?=$iconDel?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">

                        <?=  $form->field($photo, '['.$key.']text_1', [
                            'template' => '{label}<div>{input}{error}</div>',
                            'inputOptions' => ['class' => 'form-control'],
                            'labelOptions' => ['class' => 'control-label'],
                            'options' => ['tag' => 'div', 'class' => '']])->textInput(['maxlength' => true]); ?>
                        <?=  $form->field($photo, '['.$key.']text_2', [
                            'template' => '{label}<div>{input}{error}</div>',
                            'inputOptions' => ['class' => 'form-control'],
                            'labelOptions' => ['class' => 'control-label'],
                            'options' => ['tag' => 'div', 'class' => '']])->textInput(['maxlength' => true]); ?>
                        <?=  $form->field($photo, '['.$key.']text_3', [
                            'template' => '{label}<div>{input}{error}</div>',
                            'inputOptions' => ['class' => 'form-control'],
                            'labelOptions' => ['class' => 'control-label'],
                            'options' => ['tag' => 'div', 'class' => '']])->textInput(['maxlength' => true]); ?>
                        <?php /*  $form->field($photo, '['.$key.']url', [
                            'template' => '{label}<div>{input}{error}</div>',
                            'inputOptions' => ['class' => 'form-control'],
                            'labelOptions' => ['class' => 'control-label'],
                            'options' => ['tag' => 'div', 'class' => '']])->textInput(['maxlength' => true]); */?>
                    </div>
                </div>
                <?php
            }
        }?>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=\app\modules\adm\models\SliderPhotos::DIRview().'original/'?>"
     data-url="<?=URL::to(['sliders/ajaxcreatethumb'])?>" class="modal fade">
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
