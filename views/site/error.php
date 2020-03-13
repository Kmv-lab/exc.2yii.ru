<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

//$this->title = $name;
?>
<div class="site-error">

    <div class="alert alert-danger">
        <?= nl2br(Html::encode('Ошибка ' . $statusCode)) ?>
    </div>

    <?php
    switch($statusCode)
    {
        case '404':
            echo('<div>Страница не найдена!</div>');
            break;
        case '403':
            echo('<div>Вам отказано в доступе!</div>');
            break;
        case '500':
            echo('<div>Внутренняя ошибка сервера. Мы уже знаем о ней и пытаемся устранить. Благодарим за понимание!</div>');
            break;
    }

    ?>

</div>
