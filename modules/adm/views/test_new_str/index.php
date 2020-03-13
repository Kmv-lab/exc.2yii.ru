<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

?>
<?php Pjax::begin([
        'timeout' => 3000,
        'enablePushState' => false,
    ]);
?>

<div class="container">
    <h1 class="text-center">Главная в админке</h1>
    <div class="row container-elem-main-page">
        <div class="col-md-2"><p>ID</p></div>
        <div class="col-md-8"><p>Название</p></div>
    </div>
    <div class="elems-for-change">

    <?php
        $i=1;

    foreach ($model as $value) {
            ?>
        <div class="container-elem-main-page col-sm-11" id="elem-main-page<?=$i?>">
            <div class="row">
                <div class="col-md-2"><p>№ <?=$i?></p></div>
                <div class="col-md-8"><p><?=(isset($value->name) ? $value->name : "Название поля")?></p></div>
                <div class="col-md-2"><button>Удалить</button></div>
            </div>

            <?

            $form = ActiveForm::begin([
                'options' => [
                    'class' => 'adm-form-to-edit-main-page',
                    'data-pjax' => '1',
                ],
            ]);


            switch ($value["type"]) {
                case 0:
                    echo $form->field($value, 'content')->label(false)->textInput(['id' => "main_page-content_$i"]);
                break;

                case 1:
                    //code-editor
                    echo $form->field($value, 'content')->textarea([
                            'class' => 'codemirror',
                            'id' => "main_page-content_$i"
                    ])->label(false);
                break;
                case 2:
                    //WYSIWYG
                    echo $form->field($value, 'content',  [
                        'inputOptions' => ['class' => 'ckeditor'],
                        'labelOptions' => ['class' => 'col-sm-3 control-label']
                    ])->textArea(['id' => "main_page-content_wysiwyg"])->label(false);
                break;
                case 3:
                    //Изменить на поле для картинки
                    echo $form->field($value, 'content')->fileInput([
                        'multiple' => false,
                        'id' => "main_page-content_$i"
                    ])->label(false);
                    ?><img class="img-for-main-page" src="<?=$value['content']?>" alt=""><?
                break;
            }

            echo $form->field($value, 'id', ['inputOptions'=>['id' => "main_page-id_$i"]])->hiddenInput()->label(false);
            echo $form->field($value, 'type', ['inputOptions'=>['id' => "main_page-type_$i"]])->hiddenInput()->label(false);
            echo Html::submitButton('Отправить', ['data-pjax' => '1']);
            $form::end();
            $i++;
            ?>

        </div>
    <?}//закрытие цикла?>
    </div>
</div>
<?php
Pjax::end();