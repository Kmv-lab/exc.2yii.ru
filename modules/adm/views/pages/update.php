<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
<div class="page-update">

    <div class="page-form">

        <?php $form = ActiveForm::begin(['id' => 'page-form',
            'layout' => 'horizontal',
            'enableClientValidation'=>false,
            'options' => [
                // класс формы
                'class' => 'form-horizontal',
                // возможность загрузки файлов
                // 'enctype' => 'multipart/form-data'
            ],]); ?>

        <?php $pages_list = $model->getAllChildPages();
        $disabled_parent_change = $model->no_change_position ? ['inputOptions' => ['disabled' => 'disabled']] : [];
        echo $form->field($model, 'id_parent_page', $disabled_parent_change)->dropDownList($pages_list['list'], ['options'=>$pages_list['disabled'],]) ?>

        <?= $form->field($model, 'page_name', ['inputOptions' => ['class' => 'translit_source form-control']])->textInput(['maxlength' => true]) ?>

        <?= !$model->no_change_position ? $form->field($model, 'page_alias', ['inputOptions' => ['class' => 'translit_dest form-control']])->textInput(['maxlength' => true]) : ''?>

        <?= $form->field($model, 'page_menu_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'page_breadcrumbs_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'page_link_title')->textInput(['maxlength' => true]) ?>

        <?php
        $template_icon = '{label}';
        if(!empty($model->file_name)){
            $template_icon .= '<div class="col-sm-3">
<img class="img-thumbnail img-responsive" src="'.$model->DIRview().$model->file_name.'" alt="">
<a href="'.URL::to(['del_file', 'id'=>$model->id_page]).'" class="col-sm-5 deleteItem btn btn-inverse btn-default btn-xs">Удалить</a></div>';
        }
        $template_icon .= '<div>{input}{error}</div>'?>

        <?= $form->field($model, 'file_name', [
            'template' => $template_icon])->fileInput(['multiple' => false]);?>

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
        echo $form->field($model, 'page_content',  [
            'template' => $template_content,
            'inputOptions' => ['class' => 'ckeditor'],
            'labelOptions' => ['class' => 'col-sm-3 control-label']])->textArea() ?>

        <?= $form->field($model, 'page_priority')->textInput() ?>


        <?= !$model->no_change_position ? $form->field($model, 'is_active',[
            'labelOptions'=>['class'=>'col-sm-3 control-label'],
            'template' => '{label} <div class="col-sm-6 checkbox">{input}{error}{hint}</div>',
        ])->checkbox([],false) : ''?>

        <?= $form->field($model, 'show_in_menu',[
            'labelOptions'=>['class'=>'col-sm-3 control-label'],
            'template' => '{label} <div class="col-sm-6 checkbox">{input}{error}{hint}</div>',
        ])->checkbox([],false) ?>

        <?= $form->field($model, 'show_childs',[
            'labelOptions'=>['class'=>'col-sm-3 control-label'],
            'template' => '{label} <div class="col-sm-6 checkbox">{input}{error}{hint}</div>',
        ])->checkbox([],false) ?>
        <?= $form->field($model, 'show_sitemap',[
            'labelOptions'=>['class'=>'col-sm-3 control-label'],
            'template' => '{label} <div class="col-sm-6 checkbox">{input}{error}{hint}</div>',
        ])->checkbox([],false) ?>
        <legend><a href="#" class="seoLink">SEO</a></legend>
        <div class="hide seoBlock">

        <?= $form->field($model, 'seo_h1')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'seo_h1_span')->textInput(['maxlength' => true]) ?>

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
