<?php


namespace app\widgets;


use app\modules\adm\models\ExcursionPrices;
use app\modules\adm\models\Excursions;
use DateTime;
use Yii;
use yii\base\Widget;
use yii\helpers\VarDumper;

class ExcursionsWidget extends Widget
{

    public $idTown;

    public $quantityExc;

    public $isAjax = null;

    public $onlyElem = null;

    public $lastingExc = 1;

    public $filter;

    public function run()
    {

        if(isset($this->filter)){
            //vd($this->filter);
        }

        if (!$this->isAjax){
            $this->lastingExc = 0;
        }

        $daysNameArray = ExcursionPrices::getDaysArray();
        $intervals = [];
        $prices = ExcursionPrices::find()->all();

        foreach ($prices as $price) {
            $startingSerchDay = new DateTime();
            $start = new DateTime($price->start);
            $end = new DateTime($price->end);

            if (($start < $startingSerchDay) && ($startingSerchDay < $end)){

                if($startingSerchDay->format('G') > 12){
                    $startingSerchDay->modify('+1 day');
                }

                $days = [
                    1 => $price['mon'],
                    2 => $price['tue'],
                    3 => $price['wed'],
                    4 => $price['thu'],
                    5 => $price['fri'],
                    6 => $price['sat'],
                    7 => $price['sun'],
                ];

                foreach ($days as $day => $value){
                    if($value){
                        $daysArr[]=$day;
                    }
                }

                for ($i = $startingSerchDay->format('N'); $i < 8; $i++){
                    if (in_array($i, $daysArr)){
                        $nextExc = clone $startingSerchDay;
                        $nextExc = $nextExc->modify('+'. $i-$startingSerchDay->format('N') .' day');
                        break 1;
                    }
                }

                if (!isset($nextExc)){
                    for ($i = 1; $i < 8; $i++){
                        if (in_array($i, $daysArr)){
                            $day = $i - 1;
                            $nextExc = new DateTime("$day day next week");
                            break 1;
                        }
                    }
                }

                $interval = $startingSerchDay->diff($nextExc);

                $today = new DateTime();

                if ($today->format('j')==$nextExc->format('j')){
                    $nameDayOfNextExc = 'Сегодня';
                }
                elseif ($today->modify('+1 day')->format('j')==$nextExc->format('j')){
                    $nameDayOfNextExc = 'Завтра';
                }
                else{
                    $nameDayOfNextExc = $daysNameArray[$nextExc->format('N')];
                }

                $intervals[$price->id_exc] = $interval->d;
                $nextDaysExc[$price->id_exc] = [
                    'day' => $nameDayOfNextExc,
                    'price' => $price->price
                ];

            }
            unset($daysArr, $nextExc, $startingSerchDay, $today);

        }

        asort($intervals);

        $idsExc = [];
        $i=0;
        foreach ($intervals as $id_exc=>$null){
            $idsExc [$i++] = $id_exc;
        }

        for ($i = $this->lastingExc; $i<$this->quantityExc+$this->lastingExc; $i++){
            if(isset($idsExc[$i])){
                $excursions[$i] = Excursions::find()->where(['id' => $idsExc[$i]])->asArray()->one();
                $excursions[$i]['price'] = $nextDaysExc[$excursions[$i]['id']]['price'];
                $excursions[$i]['next_day'] = $nextDaysExc[$excursions[$i]['id']]['day'];
            }
        }

        if (count($intervals) <= $this->quantityExc+$this->lastingExc){
            Yii::$app->params['show_button_more_exc'] = true;
        }


        if ($this->onlyElem){

            $result ='';
            $this->lastingExc++;
            foreach ($excursions as $exc){
                $result .= $this->render('/site/excursionsHelpers/excursionOnList', [
                    'exc' => $exc,
                    'number' => $this->lastingExc
                ]);
                $this->lastingExc++;
            }
            return $result;
        }


        return $this->render('excursions', [
            'modelExc' => $excursions
        ]);
    }

}