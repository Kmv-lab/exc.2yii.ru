<?php
namespace app\widgets;

use app\modules\adm\models\GalleryPhoto;
use yii\base\Widget;
use Yii;

class Galleries extends Widget
{
    public $id_gal;
    public $galleryPhotos;
    public $modelGaleriesPhoto;

    public function run()
    {

        if ($this->modelGaleriesPhoto === null){
            $this->modelGaleriesPhoto = new GalleryPhoto();
        }

        $this->galleryPhotos = ($this->galleryPhotos !== null)? $this->galleryPhotos : 'galleries_photos';
        $columnGalleryName = ($this->galleryPhotos !== null)? 'id_gallery' : 'id_gallery';

        $SQL = "SELECT * FROM `". $this->galleryPhotos ."` WHERE `". $columnGalleryName ."` = ". $this->id_gal;
        $photos = Yii::$app->db->createCommand($SQL)->queryAll();

        //vd($photos);

        if(!empty($photos))
            return $this->render('galleries', ['photos'=>$photos, 'dir'=> $this->modelGaleriesPhoto->DIRview()]);
        else
            return false;
    }
}