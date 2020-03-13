<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use app\modules\adm\models\SliderPhotos;

class Slider extends Widget
{
    public $id;
    public $kkk;

    public function run()
    {

        //vd($this->kkk, false);

        $photos = SliderPhotos::find()
        ->where(['id_slider' => $this->id])
        ->orderBy('priority')
        ->all();

        $result =
    '<div class="slider-main">
    	<div class="owl-carousel owl-theme">';
        $resolution = '1920x1075';

        foreach ($photos AS $photo){
            $result .= '<div>
                            <img src="'.$photo->DIRview().$resolution.'/'.$photo->file_name.'"/>';
            if(!empty($photo->text_1)|| !empty($photo->text_2)|| !empty($photo->text_3)|| !empty($photo->url)){
                $result .= '
                <div class="text-wrapper">
                    <div class="container">';
                if(!empty($photo->text_1))
                    $result .= '<div class="row1">'.$photo->text_1.'</div>';
                if(!empty($photo->text_2))
                    $result .= '<div class="row2">'.$photo->text_2.'</div>';
                if(!empty($photo->text_3))
                    $result .= '<div class="row3">'.$photo->text_3.'</div>';
                $result .= '
                    </div>
                </div>';
            }
            $result .= '</div>';
        }
        $result .=
        '</div>
        <div class="script-top">
            <div class="container script">
                <div class="tv-search-form tv-moduleid-190724"></div>
            </div>
        </div>
    </div>';

        return $result;
    }
}
