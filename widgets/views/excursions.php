<?php

use app\modules\adm\models\Excursions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>


            <div class="exc-list">
            <?


            if (!empty($modelExc)){
                $i=1;
                foreach ($modelExc as $exc){
                    echo $this->render('/site/excursionsHelpers/excursionOnList', [
                        'exc' => $exc,
                        'number' => $i++
                    ]);
                }
            }
            ?>


            </div>
            <?
            if (!isset(Yii::$app->params["show_button_more_exc"])){
                echo Html::a('ПОКАЗАТЬ ЕЩЕ', Url::to(['more_exc', 'lasting_exc' => 1]), ['class' => 'btn btn_orange exc-sec__btn more-exc']);
            }
            ?>


