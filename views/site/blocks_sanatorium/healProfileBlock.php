<?php

/*use app\controllers\SiteController;

$allHeal = SiteController::getSanatoriunHealBase($idSan);

$mainHeal = $allHeal[0];
$optionHeal = $allHeal[1];*/

//vd(Yii::$app->params);
?>
<div class="sanatorium-block">
    <div class="sanatorium-heal-profile center-content">
        <div class="sanatorium-heal-elem sanatorium-heal-main">
            <?
                foreach ($mainHeal as $value){
                    ?>

                        <div class="elem-sanatorium-profile">
                            <div class="icon-elem-sanatorium-profile">
                                <img class="icon-elem-sanatorium-profile" src="<?=Yii::$app->params['path_to_official_images']?>plaster.png" alt="">
                            </div>
                            <div class="name-elem-sanatorium-profile">
                                <?=$value['name']?>
                            </div>
                        </div>

                    <?
                }
            ?>
        </div>
        <div class="sanatorium-heal-elem sanatorium-heal-option">
            <?
                foreach ($optionHeal as $value){
                    ?>

                        <div class="elem-sanatorium-profile">
                            <div class="icon-elem-sanatorium-profile">
                                <img class="icon-elem-sanatorium-profile" src="<?=Yii::$app->params['path_to_official_images']?>plaster.png" alt="">
                            </div>
                            <div class="name-elem-sanatorium-profile">
                                <?=$value['name']?>
                            </div>
                        </div>

                    <?
                }
            ?>
        </div>
    </div>
</div>
