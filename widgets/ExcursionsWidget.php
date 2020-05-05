<?php


namespace app\widgets;


use app\modules\adm\models\ExcursionCategory;
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

    public $lastingExc = 0;

    public $filter;

    public function run()
    {

        $sqlSelect = 'SELECT * ';
        $sqlFrom = ' FROM prices ';
        $sqlJoin = ' JOIN excursions
                ON prices.id_exc = excursions.id ';
        $sqlWhere = ' WHERE prices.start < CURDATE() 
                AND prices.`end` > CURDATE() ';
        $sqlOrder = ' ORDER BY excursions.time_start ASC ';

        if((!empty($this->filter)) && ($this->filter->date)){
            $dayFilter = new DateTime($this->filter->date);
            $sqlWhere = 'WHERE prices.start < '. $dayFilter->format('Ymd') .' 
                AND prices.`end` > '. $dayFilter->format('Ymd') .' ';
            switch ($dayFilter->format('N')){
                case 1:
                    $sqlWhere .= ' AND prices.mon = 1 ';
                    break;
                case 2:
                    $sqlWhere .= ' AND prices.tue = 1 ';
                    break;
                case 3:
                    $sqlWhere .= ' AND prices.wed = 1 ';
                    break;
                case 4:
                    $sqlWhere .= ' AND prices.thu = 1 ';
                    break;
                case 5:
                    $sqlWhere .= ' AND prices.fri = 1 ';
                    break;
                case 6:
                    $sqlWhere .= ' AND prices.sat = 1 ';
                    break;
                case 7:
                    $sqlWhere .= ' AND prices.sun = 1 ';
                    break;
            }
            //vd($dayFilter->format('Ymd'));
        }

        if(!empty($this->filter) && ($this->filter->type)){
            $sqlJoin .= ' JOIN exc_category ON exc_category.id_exc = prices.id_exc ';
            $sqlWhere .= ' AND exc_category.id_category = '.$this->filter->type.' ';
        }

        if(!empty($this->filter) && ($this->filter->duration)){
            switch ($this->filter->duration){
                case 1://менее 3х часов
                    $sqlWhere .= ' AND excursions.duration < 3 ';
                    break;
                case 2://менее 6ти
                    $sqlWhere .= ' AND excursions.duration >= 3 AND excursions.duration < 6 ';
                    break;
                case 3://более 6ти
                    $sqlWhere .= ' AND excursions.duration >= 6 ';
                    break;
            }
        }

        $sql = $sqlSelect.$sqlFrom.$sqlJoin.$sqlWhere.$sqlOrder;

        $matrix = Yii::$app->db->createCommand($sql)->queryAll();//Получение активных Экскурсий с ценами

        $startingSerchDay = new DateTime();
        $CurDay = $startingSerchDay->format('N');
        if($startingSerchDay->format('G') > 15){
            $startingSerchDay->modify('+1 day');
            $CurDay = $startingSerchDay->format('N');
        }
        $today = new DateTime();

        $daysNameArray = ExcursionPrices::getDaysArray();

        $daysNameArrayEng = [
            1 => 'mon',
            2 => 'tue',
            3 => 'wed',
            4 => 'thu',
            5 => 'fri',
            6 => 'sat',
            7 => 'sun',
        ];

        foreach ($matrix as $exc){

            for ($i = 1; $i<8; $i++){
                if($exc[$daysNameArrayEng[$i]] == 1){
                    if (isset($this->filter->date) && $this->filter->date){
                        $exc['next_day'] = $this->filter->date;
                    }
                    else{
                        if ($today->format('N') == $i){
                            $exc['next_day'] = 'Сегодня';
                        }
                        elseif (($today->format('N')+1) == $i){
                            $exc['next_day'] = 'Завтра';
                        }
                        else{
                            $exc['next_day'] = $daysNameArray[$i];
                        }
                    }
                    $excursions[$i][] = $exc;
                }
            }
        }//разбол полученного массива по дням недени.

        $count = 1;
        $duplicates = [];

        for ($i = $CurDay; $count<8; $count++, $i++){
            if (isset($excursions[$i])){
                foreach ($excursions[$i] as $exc){
                    if(array_search($exc['id'], $duplicates) === false){
                        $duplicates[] = $exc['id'];
                        $temp[] = $exc;
                    }
                }
            }


            $i = $i == 7 ? 1 : $i;
        }

        if(!isset($temp)){
            echo "<span style='color: #0a0a0a'>Нет подходящих Экскурсий</span>";
            return;
        }

        if ($this->onlyElem){
            //vd(count($temp), false);
            //vd($this->quantityExc+$this->lastingExc);
        }

        if (count($temp) > $this->quantityExc+$this->lastingExc){
            Yii::$app->params['show_button_more_exc'] = true;
        }

        if ($this->onlyElem){

            $result ='';
            $this->lastingExc++;
            $temp = array_slice($temp, $this->lastingExc-1, $this->quantityExc);
            foreach ($temp as $exc){
                $result .= $this->render('/site/excursionsHelpers/excursionOnList', [
                    'exc' => $exc,
                    'number' => $this->lastingExc,
                    'filterDate' => isset($this->filter->date) && $this->filter->date ? true : false,
                ]);
                $this->lastingExc++;
            }
            return $result;
        }
        else{
            return $this->render('excursions', [
                'modelExc' => array_slice($temp, 0, $this->quantityExc),
                'filterDate' => isset($this->filter->date) ? true : false,
            ]);
        }
    }

    private function filterExcByType($type){
        $typeName = Excursions::getCategories($type);

        $SQL = 'SELECT `id_exc` FROM `exc_category` WHERE `id_category` = :id';
        $excursionsCategory = Yii::$app->db->createCommand($SQL)->bindValue(':id', $type)->queryAll();


    }

}