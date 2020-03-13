<?php

use yii\helpers\Url;

if($isDropDown){
    //вывод в виде dropDownList
    $i = 0;
    echo !empty($prices) ? "<div class=\"block-prices\"><h1>Цены на общетерапевтические путёвки</h1>" : '';
    foreach ($prices as $san){?>
        <div class="san-price-block">
            <div id="sanatorium<?=$i?>" class="elem-of-table-price">
                <h1><?=$san['name']?></h1>
                <span>от <?=$san['min_price']?></span>
                <div class="btn btn-success">Посмотреть цены</div>
            </div>
            <div class="prices-block" id="prices<?=$i++?>">
                <?=$this->render('/site/blocks_sanatorium/priceBlock', [
                    'fullPriceArray' => $san['price']
                ]);?>
                <a href="<?=Url::to(['sanatorium/'.$san['alias']])?>"><button class="btn-primary">Страница санатория</button></a>
            </div>
        </div>
    <?}
    echo !empty($prices) ? "</div>" : '';

    echo !empty($pricesSecond) ?  "<div class=\"block-prices\"><h1>Цены на оздоровительные путёвки</h1>" : '';
    foreach ($pricesSecond as $san){?>
        <div class="san-price-block">
            <div id="sanatorium<?=$i?>" class="elem-of-table-price">
                <h1><?=$san['name']?></h1>
                <span>от <?=$san['min_price']?></span>
                <div class="btn btn-success">Посмотреть цены</div>
            </div>
            <div class="prices-block" id="prices<?=$i++?>">
                <?=$this->render('/site/blocks_sanatorium/priceBlock', [
                    'fullPriceArray' => $san['price']
                ]);?>
                <a href="<?=Url::to(['sanatorium/'.$san['alias']])?>"><button class="btn-primary">Страница санатория</button></a>
            </div>
        </div>
    <?}
    echo !empty($pricesSecond) ? "</div>" : '';

}
else{
    //вывод в виде простой таблицы
    echo !empty($prices) ? "<div class=\"block-prices\"><h1>Цены на общетерапевтические путёвки</h1>" : '';
    foreach ($prices as $san){
        echo $this->render('/site/blocks_sanatorium/priceBlock', [
                    'fullPriceArray' => $san['price']
                ]);
    }
    echo !empty($pricesSecond) ? "</div>" : '';


    echo !empty($pricesSecond) ?  "<div class=\"block-prices\"><h1>Цены на оздоровительные путёвки</h1>" : '';
    foreach ($pricesSecond as $san){
        echo $this->render('/site/blocks_sanatorium/priceBlock', [
            'fullPriceArray' => $san['price']
        ]);
    }
    echo !empty($pricesSecond) ? "</div>" : '';
}
