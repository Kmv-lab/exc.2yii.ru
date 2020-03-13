
<?php
if($fullPriceArray) {
    ?>
    <div class="sanatorium-block">
        <div class="sanatorium-room-price-table-container">
            <div class="sanatorium-room-price-table">
                <div class="container-scroll sanatorium-room-price-table">
                    <div class="s-table-row">
                        <div class="s-table-name-row">Номера</div>
                        <?php
                        //Очень сложная ментальная эквелибристика, если кто-то разберёт и напишет лучше - буду благодарен.
                        //Я просто не особо обременён интелектом :(
                        for ($i = 0; $i < $fullPriceArray[1]; $i++) {
                            ?>
                            <div class="s-table-elem">
                                <?
                                $monthStart = Yii::$app->params['monhts_to_russian'][date('n', $fullPriceArray[0][$i]['start']) - 1];
                                $monthEnd = Yii::$app->params['monhts_to_russian'][date('n', $fullPriceArray[0][$i]['end']) - 1];
                                $dates = date("d", $fullPriceArray[0][$i]['start']) . ' ' . $monthStart . ' ' . date("Y", $fullPriceArray[0][$i]['start']);
                                $dates .= "<br>";
                                $dates .= date("d", $fullPriceArray[0][$i]['end']) . ' ' . $monthEnd . ' ' . date("Y", $fullPriceArray[0][$i]['end']);
                                echo $dates;
                                ?>
                            </div>
                        <? } ?>
                    </div>
                    <?php
                    for ($i = 0; $i < count($fullPriceArray[0]); $i += $fullPriceArray[1]) {
                        ?>
                        <div class="s-table-row">
                            <div class="s-table-name-row"><?= $fullPriceArray[0][$i]['name'] ?></div>
                            <?
                            for ($y = 0; $y < $fullPriceArray[1]; $y++) {
                                ?>
                                <div class="s-table-elem"><?= $fullPriceArray[0][$i + $y]['main'] ?></div>
                            <? } ?>
                        </div>
                        <?
                    } ?>
                </div>
            </div>
            <br>
        </div>
    </div>
    <? }?>

