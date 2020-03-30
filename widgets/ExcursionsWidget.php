<?php


namespace app\widgets;


use app\modules\adm\models\ExcursionPrices;
use app\modules\adm\models\Excursions;
use Yii;
use yii\base\Widget;

class ExcursionsWidget extends Widget
{

    public $idTown;

    public $quantityExc;

    public $isAjax = null;

    public $onlyElem = null;

    public $lastingExc = 1;

    public function run()
    {

        //$model = Excursions::find()->each(10);
        //vd($model);
        $daysNameArray = ExcursionPrices::getDaysArray();

        if(!$this->quantityExc){
            $this->quantityExc = 3;
        }

        $model = $this->isAjax ? Excursions::find()->where([])->limit($this->quantityExc)->asArray()->offset($this->lastingExc)->all() : Excursions::find()->where([])->limit($this->quantityExc)->asArray()->all();

        foreach ($model as $key => $value){

            $pricesModel = ExcursionPrices::find()->where(['id_exc' => $value['id']])->asArray()->all();
            foreach ($pricesModel as $priceElem){
                $start = strtotime($priceElem['start']);
                $end = strtotime($priceElem['end']);
                $now = strtotime('now');

                if (($start <= $now) && ($now <= $end)){

                    $model[$key]['prise'] = $priceElem['price'];

                    $daysExc = [
                        1 => $priceElem['mon'],
                        2 => $priceElem['tue'],
                        3 => $priceElem['wed'],
                        4 => $priceElem['thu'],
                        5 => $priceElem['fri'],
                        6 => $priceElem['sat'],
                        7 => $priceElem['sun'],
                    ];

                    for ($i = date('N'); $i <= 7; $i++){
                        if ($daysExc[$i]){
                            $model[$key]['next_day'] = $daysNameArray[$i];
                            break 1;
                        }
                    }

                    if(!isset($model[$key]['next_day'])) {
                        for ($i = 1; $i <= 7; $i++) {
                            if ($daysExc[$i]) {
                                $model[$key]['next_day'] = $daysNameArray[$i];
                                break 1;
                            }
                        }
                    }
                }
            }
        }

        if ($this->onlyElem){
            $result ='';
            $this->lastingExc++;
            foreach ($model as $exc){
                $result .= $this->render('/site/excursionsHelpers/excursionOnList', [
                    'exc' => $exc,
                    'number' => $this->lastingExc
                ]);
                $this->lastingExc++;
            }
            return $result;
        }

        return $this->render('excursions', [
            'modelExc' => $model
        ]);
    }

}