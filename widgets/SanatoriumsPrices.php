<?php


namespace app\widgets;


use app\models\SansPrev;
use app\modules\adm\models\Rooms;
use Yii;
use yii\base\Widget;

class SanatoriumsPrices extends Widget
{
    public $idSan = 0;
    public $isDropDown = 1;

    public function run()
    {
        //$idCity = Yii::$app->params['id_current_city'];
        $idCity = 0;

        $sanatoriumsAll = new SansPrev();
        if($this->idSan == 0){
            $sanatoriums = $sanatoriumsAll->find()->where(['id_city' => $idCity, 'is_active' => 1])->asArray()->all();
        }
        else{
            $sanatoriums = $sanatoriumsAll->find()->where(['id_city' => $idCity, 'is_active' => 1, 'id' => $this->idSan])->asArray()->all();
        }

        foreach ($sanatoriums as $sanatorium){
            $idsInMainTable[] = $sanatorium['id_in_main_table'];
        }

        $SQL = 'SELECT `price_json` FROM `sans` WHERE `id_san` IN ('. implode(',', $idsInMainTable) .');  ';
        $minPriceJson = Yii::$app->dbResort->createCommand($SQL)->queryAll();

        $i = 0;
        foreach ($sanatoriums as $sanatorium){
            $minPriceSan = json_decode($minPriceJson[$i]['price_json'], true);

            $pricesSan[$i]['alias'] = $sanatorium['alias'];
            $pricesSan[$i]['name'] = $sanatorium['name'];
            $pricesSan[$i]['min_price'] = $minPriceSan[5];
            $dataRooms = $this->getRoomsDataForSanatorium($sanatorium['id_in_main_table']);
            $pricesSan[$i]['price'] = $this->getPricesForSanatorium($dataRooms);

            $incompletePriceArray[$i]['price'] = $this->getPricesForSanatorium($dataRooms, 4);
            if ($incompletePriceArray[$i]['price'] == 0){
                unset($incompletePriceArray[$i]);
            }
            else{
                $incompletePriceArray[$i]['alias'] = $sanatorium['alias'];
                $incompletePriceArray[$i]['name'] = $sanatorium['name'];
                $incompletePriceArray[$i]['min_price'] = isset($minPriceSan[4]) ? $minPriceSan[4] : 'Уточняйте у менеджера';
            }
            $i++;
        }

        return $this->render('sanatoriumPrices', [
            'isDropDown' => $this->isDropDown,
            'prices' => $pricesSan,
            'pricesSecond' => $incompletePriceArray
        ]);
    }

    /**
     *
     *
     * @param $idSan int id нужного санатория
     *
     * @return array/false подготовленный массив данных о комнатах
     */
    public function getRoomsDataForSanatorium($idSan){
        $SQL = 'SELECT * FROM `rooms` WHERE `id_san` = :id AND `is_active` = 1';
        $rooms = Yii::$app->dbResort->createCommand($SQL)->bindValues([':id' => $idSan, ] )->queryAll();

        $dataRoomsIds = [];
        foreach ($rooms as $room){
            $dataRoomsIds[$room['id_room']] = [
                'name' =>$room['name']
            ];
        }

        return $dataRoomsIds;
    }

    /**
     *
     * @param $dataRooms array массив данных о комнатах, для получения названия комнат
     * @param $type int Тип проживания(4 - Проживание и питание, 5 - Проживание, питание и лечение)
     *
     * @return mixed - подготовленный массив данных о ценах на комнаты[0] и временные переоды[1] колличество, или boolean(false) в случае ошибки
     */
    private function getPricesForSanatorium($dataRooms, $type=5){

        $dataRoomsIds = array_keys($dataRooms);

        $SQL = "SELECT `id_room`, `id_when`, `main`, `add`, `alone`
                FROM `room_price` 
                WHERE `id_room` IN (". implode(',', $dataRoomsIds) .") AND `year_start` = 0 AND `year_end` = 0 AND `type` = :type_room";
        $prices = Yii::$app->dbResort->createCommand($SQL)->bindValues([':type_room' => $type])->queryAll();

        $uniquePriceTime = [];

        foreach ($prices as $key=>$price){
            $uniquePriceTime[] = $price['id_when'];
        }

        $uniquePriceTime = array_unique($uniquePriceTime);
        sort($uniquePriceTime);
        $SQL = 'SELECT `start`, `end` FROM `sans_price_time` WHERE `id_time` = :id;';
        for ($i=0; $i<count($uniquePriceTime); $i++){
            $timePrice[$uniquePriceTime[$i]] = Yii::$app->dbResort->createCommand($SQL)->bindValues([':id' => $uniquePriceTime[$i], ] )->queryOne();
        }

        $preparedPrices = [];
        $i = 0;


        foreach ($prices as $price){
            foreach ($timePrice as $key=>$value){
                if ($price['id_when']==$key){
                    $preparedPrices[$i] = array_merge($value, $price);
                }
            }
            $preparedPrices[$i] = array_merge($preparedPrices[$i],['name' => $dataRooms[$price['id_room']]['name']]);
            $i++;
        }

        foreach ($preparedPrices as $key => $value){
            $idRooms[$key] = $value['id_room'];
            $idStart[$key] = $value['start'];
        }

        if (empty($preparedPrices)){
            return false;
        }
        array_multisort($idRooms, SORT_ASC, $idStart, SORT_ASC, $preparedPrices);


        $data[0] = $preparedPrices;
        $data[1] = count($uniquePriceTime);

        return $data;
    }
}