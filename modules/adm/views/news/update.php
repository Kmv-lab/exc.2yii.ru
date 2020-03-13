<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
<div class="news-update">
    <div class="news-form">

        <?php $form = ActiveForm::begin(['id' => 'news-form',
            'layout' => 'horizontal',
            'enableClientValidation'=>false,
            'options' => [
                // класс формы
                'class' => 'form-horizontal',
                // возможность загрузки файлов
                // 'enctype' => 'multipart/form-data'
            ],]); ?>

        <?= $form->field($model, 'name', ['inputOptions' => ['class' => 'translit_source form-control']])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'alias', ['inputOptions' => ['class' => 'translit_dest form-control']])->textInput(['maxlength' => true]) ?>


        <?= $form->field($model, 'date_publication')->textInput(['class' => 'datepicker form-control',
            'value' => $model->date_publication > 0 ? date('d.m.Y',$model->date_publication) : '']) ?>

        <?= $form->field($model, 'file_name')->fileInput(['multiple' => false]);?>
        <?php
        if(!empty($model->file_name)) {

            $iconDel = '<span class="glyphicon glyphicon-remove"></span>';
            $iconEdit = '<span class="glyphicon glyphicon-pencil"></span>';
            $iconOK = '<span class="glyphicon glyphicon-ok"></span>';

            $this->registerJsFile('/js/jcrop/jquery.Jcrop.min.js', ['depends' => ['yii\web\JqueryAsset']]);
            $this->registerCssFile('/js/jcrop/jquery.Jcrop.min.css');
            $i = 1;
            $delUrl = URL::to(['delete_photo', 'id' => $model->id_news]);
            $resetUrl = URL::to(['reset_photo', 'id' => $model->id_news]);
            $count_str = ceil(count($resolutions) / 3); ?>
            <div class="form-group image_thumb">
                <div class="col-sm-12">
                    <div class="col-sm-9">
                        <?php for ($y = 1; $y <= $count_str; $y++) { ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <?php for ($x = 1; $x <= 3; $x++) {
                                        $key_resolution = (($y - 1) * 3) + $x - 1;
                                        if (!empty($resolutions[$key_resolution])) {
                                            $resolution = $resolutions[$key_resolution]; ?>
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
                            <select class="form-control input-sm" name="Photo_ratio_<?= $model->id_news ?>"
                                    id="Photo_ratio_<?= $model->id_news ?>">
                                <option value="0" selected="selected">Все разрешения</option>
                                <?php foreach ($resolutions AS $resolution) { ?>
                                    <option value="<?php echo $resolution ?>"><?php echo $resolution ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group"><a href="#" class="btn btn-primary btn-xs show-dialog-thumb"
                                                   data-id="<?= $model->id_news ?>" data-file_name="<?= $model->file_name ?>">Редактировать
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

        <?php
        $template_content = '
            <div class="form-group">
                {label}
                <div class="col-lg-9 col-sm-9">
                    <a href="#" class="btn btn-default btn-xs showTpls" ckId="page-page_content">Вставить Шаблончик <span class="glyphicon glyphicon-arrow-down"></span></a> 
                    <a href="#" class="btn btn-default btn-xs showSnippets" ckId="page-page_content">Вставить Сниппет <span class="glyphicon glyphicon-arrow-down"></span></a>
                    <a href="#" class="btn btn-default btn-xs showGalleries" ckId="page-page_content">Вставить Галерею <span class="glyphicon glyphicon-arrow-down"></span></a>
                    <div class="tpls hide btn-group-vertical" ckId="page-page_content">';
        $tplts = Yii::$app->db->createCommand('Select * FROM templates')->queryAll();
        foreach ($tplts AS $tpl){
            $template_content .= ' <div class="TplLink btn btn-primary btn-xs" href="#" ckId="page-page_content" rel="'.$tpl['id_tpl'].'">'.$tpl['tpl_name'].'</div>
                                <span class="TplContent hide" rel="'.$tpl['id_tpl'].'" >'.$tpl['tpl_content'].'</span>';
        }
        $template_content .=  '
                    </div>
                    <div class="snippets hide btn-group-vertical" ckId="page-page_content">';
        $snippets = Yii::$app->db->createCommand('Select * FROM snippets')->queryAll();
        foreach ($snippets AS $snip){
            $template_content .= '
                                <div class="snipLink btn btn-primary btn-xs" href="#" ckId="page-page_content" >
                                        <span class="hide"><div>[*Snippets|{"alias":"'.$snip['snippet_alias'].'"}**]</div></span>'.$snip['snippet_name'].'
                                </div>';
        }
        $template_content .=  '                    
                     </div>
                     <div class="galleries hide btn-group-vertical" ckId="page-page_content">';
        $galleries = Yii::$app->db->createCommand('Select * FROM galleries')->queryAll();
        foreach ($galleries AS $gal){
            $template_content .=  '
                        <div class="galLink btn btn-primary btn-xs" href="#" ckId="page-page_content" >
                            <span class="hide"><div>[*Galleries|{"id_gal":"'.$gal['id_gallery'].'"}**]</div></span>
                            '.$gal['name'].'
                        </div>';
        }
        $template_content .=  '
                     </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    {input}{error}
                </div>
            </div>';
        echo $form->field($model, 'content',  [
            'template' => $template_content,
            'inputOptions' => ['class' => 'ckeditor'],
            'labelOptions' => ['class' => 'col-sm-3 control-label']])->textArea() ?>

        <?= $form->field($model, 'is_active',[
            'labelOptions'=>['class'=>'col-sm-3 control-label'],
            'template' => '{label} <div class="col-sm-6 checkbox">{input}{error}{hint}</div>',
        ])->checkbox([],false)?>

        <legend><a href="#" class="seoLink">SEO</a></legend>

        <div class="hide seoBlock">
            <?= $form->field($model, 'seo_h1')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'seo_description')->textArea(['maxlength' => true]) ?>

            <?= $form->field($model, 'seo_keywords')->textArea(['maxlength' => true]) ?>
        </div>


        <div class="form-group">
            <?= Html::submitButton($model->getIsNewRecord() ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
<div id="dialog-thumb"  title="Редактирование миниатюры" data-big="<?=\app\modules\adm\models\News::DIRview().'original/'?>"
     data-url="<?=URL::to(['news/ajaxcreatethumb'])?>" class="modal fade">
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
