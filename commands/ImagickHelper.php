<?php

namespace app\commands;

use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class ImagickHelper
{
    /*
     * @property object Imagick $image
     * */
    public static function autorotate($image){
        $Orientation = $image->getImageOrientation();
        $image->setImageOrientation(\Imagick::ORIENTATION_UNDEFINED);
        switch ($Orientation) {
            case \Imagick::ORIENTATION_TOPLEFT:
                break;
            case \Imagick::ORIENTATION_TOPRIGHT:
                $image->flopImage();
                break;
            case \Imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateImage("#000", 180);
                break;
            case \Imagick::ORIENTATION_BOTTOMLEFT:
                $image->flopImage();
                $image->rotateImage("#000", 180);
                break;
            case \Imagick::ORIENTATION_LEFTTOP:
                $image->flopImage();
                $image->rotateImage("#000", -90);
                break;
            case \Imagick::ORIENTATION_RIGHTTOP:
                $image->rotateImage("#000", 90);
                break;
            case \Imagick::ORIENTATION_RIGHTBOTTOM:
                $image->flopImage();
                $image->rotateImage("#000", 90);
                break;
            case \Imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateImage("#000", -90);
                break;
            default: // Invalid orientation
                break;
        }
        return $image;
    }

    /**
     * @param ActiveRecord $model
     * @param int $resize
     * @param string $name_param
     * @param array $skip_dop
     * @return array the loaded model
     * @throws NotFoundHttpException if the Imagick cannot be found
     */
    public static function save($model, $resize=0, $name_param = 'file_name', $skip_dop=[]){
        //vd($model);
        if(!class_exists('\Imagick')){
            throw new NotFoundHttpException('Imagick не найден.');
        }
        $photo = $model->$name_param;
        $name_of_file = $model->$name_param = strtolower(md5($photo->baseName.mktime()).'.'.$photo->extension);//Уникальное имя для файла
        $i = 0;
        while (!$model->validate() && $i < 100){//ЗАЧЕМ??????????????????
            $i++;
            $name_of_file = $model->$name_param = strtolower(md5($photo->baseName.$i.mktime()).'.'.$photo->extension);
        }
        if($model->validate()){
            $DIR = $model->DIR();
            $newImage = new \Imagick($photo->tempName);
            $newImage = self::autorotate($newImage);
            $geometry = $newImage->getImageGeometry();
            $original_folder = 'original';
            if($geometry['width'] > 1920){
                $newImage->thumbnailImage(1920, null );
                $geometry = $newImage->getImageGeometry();
            }
            if($geometry['height'] > 1440){
                $newImage->thumbnailImage(null, 1440 );
                $geometry = $newImage->getImageGeometry();
            }
            $newImage->writeImage($DIR.$original_folder.'/'.$name_of_file);
            if($resize){
                $skip = ['.', '..', $original_folder];
                $skip = array_merge($skip, $skip_dop);
                $scan = scandir($DIR);
                foreach($scan as $key=>$resolution) {
                    if(!in_array($resolution, $skip)){
                        $newImage->destroy();
                        $newImage = new \Imagick($DIR.$original_folder.'/'.$name_of_file);
                        $height = explode('x', $resolution);
                        $width = $height[0];
                        $height = $height[1];
                        $coeffR = $width/$height;
                        $coeff = $geometry['width']/$geometry['height'];
                        if($coeffR < $coeff){
                            $height_new = $geometry['height'];
                            $width_new = $geometry['height'] * $coeffR;
                        }else{
                            $height_new = $geometry['width']/$coeffR;
                            $width_new = $geometry['width'];
                        }
                        $startx = round(($geometry['width']-$width_new)/2);
                        $starty = round(($geometry['height']-$height_new)/2);
                        $newImage->cropImage($width_new, $height_new, $startx, $starty);
                        $newImage->thumbnailImage($width, $height, false, false);
                        $newImage = self::compress($newImage);
                        $newImage->writeImage($DIR.$width.'x'.$height.'/'.$name_of_file);
                    }
                }
            }
            $newImage->destroy();
            $newImage = new \Imagick($DIR.$original_folder.'/'.$name_of_file);
            if(!empty($skip_dop)){
                $resolution = $skip_dop[0];
                $height = explode('x', $resolution);
                $width = $height[0];
                $height = $height[1];
                $coeffR = $width/$height;
                $coeff = $geometry['width']/$geometry['height'];
                if($coeffR < $coeff){
                    $vertical = 0;
                }else{
                    $vertical = 1;
                }
                if($geometry['width'] > $width){
                    $newImage->thumbnailImage($width, null );
                }
                if($geometry['height'] > $height){
                    $newImage->thumbnailImage(null, $height );
                }
                $newImage = self::compress($newImage);
                $newImage->writeImage($DIR.$width.'x'.$height.'/'.$name_of_file);
                $newImage->destroy();
                $columns['vertical'] = $vertical;
            }
        }
        return $model->attributes;
    }


   /* public function saveNewPhoto($id, $photo, $quality, $DIR, $table, $id_name, $resize, $alt = '', $main = 0, $skip_dop=array()){//skip_dop - разрешение исключение, пока ток первый в массиве работает
        $name_of_file = strtolower(md5($photo->name.mktime()).'.'.$photo->extensionName);
        $columns = array(
            $id_name    =>  $id,
            'file_name'     =>  $name_of_file,
            'priority'      =>  99,
        );
        if(!empty($alt)){
            $alt = strip_tags($alt);
            $alt = htmlspecialchars($alt);
            $alt = mysql_escape_string($alt);
            $columns['alt'] = $alt;
        }
        if($main)
            $columns['is_main'] = 1;

        $newImage = new \Imagick($photo->tempName);
        $newImage = self::autorotate($newImage);
        $geometry = $newImage->getImageGeometry();
        if($geometry['width'] > 1920){
            $newImage->thumbnailImage(1920, null );
            $geometry = $newImage->getImageGeometry();
        }
        if($geometry['height'] > 1440){
            $newImage->thumbnailImage(null, 1440 );
            $geometry = $newImage->getImageGeometry();
        }
        $newImage->writeImage($DIR.'big/'.$name_of_file);
        if($resize){
            $skip = array('.', '..', 'big');
            $skip = array_merge($skip, $skip_dop);
            $scan = scandir($DIR);
            foreach($scan as $key=>$resolution) {
                if(!in_array($resolution, $skip)){
                    $newImage->destroy();
                    $newImage = new \Imagick($DIR.'big/'.$name_of_file);
                    $height = explode('x', $resolution);
                    $width = $height[0];
                    $height = $height[1];
                    $coeffR = $width/$height;
                    $coeff = $geometry['width']/$geometry['height'];
                    if($coeffR < $coeff){
                        $height_new = $geometry['height'];
                        $width_new = $geometry['height'] * $coeffR;
                    }else{
                        $height_new = $geometry['width']/$coeffR;
                        $width_new = $geometry['width'];
                    }
                    $startx = round(($geometry['width']-$width_new)/2);
                    $starty = round(($geometry['height']-$height_new)/2);
                    $newImage->cropImage($width_new, $height_new, $startx, $starty);
                    $newImage->thumbnailImage($width, $height, false, false);
                    $newImage = self::compress($newImage);
                    $newImage->writeImage($DIR.$width.'x'.$height.'/'.$name_of_file);
                }
            }
        }
        $newImage->destroy();
        $newImage = new \Imagick($DIR.'big/'.$name_of_file);
        if(!empty($skip_dop)){
            $resolution = $skip_dop[0];
            $height = explode('x', $resolution);
            $width = $height[0];
            $height = $height[1];
            $coeffR = $width/$height;
            $coeff = $geometry['width']/$geometry['height'];
            if($coeffR < $coeff){
                $vertical = 0;
            }else{
                $vertical = 1;
            }
            if($geometry['width'] > $width){
                $newImage->thumbnailImage($width, null );
            }
            if($geometry['height'] > $height){
                $newImage->thumbnailImage(null, $height );
            }
            $newImage = self::compress($newImage);
            $newImage->writeImage($DIR.$width.'x'.$height.'/'.$name_of_file);
            $newImage->destroy();
            $columns['vertical'] = $vertical;
        }
        Yii::app()->db->createCommand()->insert($table,$columns);
        $new_id = Yii::app()->db->getLastInsertId();
        $log = new FormLog;
        $log->log($table,$new_id,'new');
        return $new_id;
    } */
    /*
     * @property object Imagick $newImage
     * */
    public function compress($newImage){
        if($newImage->getImageMimeType() == 'image/jpeg'){
            $newImage->transformImageColorspace(\Imagick::COLORSPACE_RGB);
            $newImage->setSamplingFactors(array('2x2', '1x1', '1x1'));
            $newImage->setImageCompressionQuality(85);
            if($newImage->getImageLength()/1024 > 10)
                $newImage->setInterlaceScheme(\Imagick::INTERLACE_PLANE);
        }
        $newImage->stripImage();
        return $newImage;
    }

    /**
     * @param array $post
     * @param ActiveRecord $model
     * @param string $name_param
     * @param array $skip_dop
     * @return string result
     * @throws NotFoundHttpException if the Imagick cannot be found
     */
    public static function Thumb($post, $model, $name_param = 'file_name', $skip_dop=array()){
        if(!class_exists('\Imagick')){
            throw new NotFoundHttpException('Imagick не найден.');
        }
        $x1 = round($post['x1']);//0
        $y1 = round($post['y1']);//1
        $x2 = round($post['x2']);//504
        $y2 = round($post['y2']);//337
        $ratio = $post['r'];
        $width = $x2-$x1;//504
        $height= $y2-$y1;//336
        $image = $model->attributes;
        $DIR = $model->DIR();
        $folder_origin = 'original';
        $newImage = new \Imagick($DIR.$folder_origin.'/'.$image[$name_param]);
        //vd($newImage);
        $geometry = $newImage->getImageGeometry();
        //vd($post);
        if($ratio == 0){
            $skip = array('.', '..', $folder_origin);
            $skip = array_merge($skip, $skip_dop);
            $scan = scandir($DIR);
            foreach($scan as $key=>$resolution) {
                if(!in_array($resolution, $skip)){
                    $heightR = explode('x', $resolution);
                    $widthR = $heightR[0];
                    $heightR = $heightR[1]; //необходимый итоговый размер
                    // начало новой обрезки
                    $ratioCoeff =  $width/$height; // коэффициент выделенной части
                    $ratioCoeffR = $widthR/$heightR;  // коэффициент итогового размера
                    if($ratioCoeffR > $ratioCoeff){ // расчет что и как обрезать
                        $heightCrop = $height;
                        $widthCrop = ceil($heightCrop * $ratioCoeffR);
                        $x1Crop = ceil($x1 - ($widthCrop-$width)/2);
                        $y1Crop = $y1;
                    }else{
                        $widthCrop = $width;
                        $heightCrop = ceil($widthCrop/$ratioCoeffR);
                        $x1Crop = $x1;
                        $y1Crop = ceil($y1 - ($heightCrop-$height)/2);
                    }
                    if($x1Crop >= 0 && $y1Crop >= 0 && ($x1Crop+$widthCrop) <= $geometry['width'] && ($y1Crop+$heightCrop) <= $geometry['height']){
                        $newImage->cropImage((int)$widthCrop, (int)$heightCrop, (int)$x1Crop, (int)$y1Crop);
                        $newImage->thumbnailImage($widthR, $heightR, false, false);
                    }else{
                        // конец новой обрезки
                        $newImage->cropImage($width, $height, $x1, $y1);
                        if(($widthR/$heightR) < ($width/$height)){
                            $w = ceil($heightR * ($width/$height));
                            $newImage->thumbnailImage($w, $heightR, false, false);
                            $geometryThumb = $newImage->getImageGeometry();
                            $startx = round(($geometryThumb['width']-$widthR)/2);
                            $starty = round(($geometryThumb['height']-$heightR)/2);
                            $newImage->cropImage($widthR, $heightR, $startx, $starty);
                        }else{
                            $h = ceil($widthR/($width/$height));
                            $newImage->thumbnailImage($widthR, $h, false, false);

                            $geometryThumb = $newImage->getImageGeometry();
                            $startx = round(($geometryThumb['width']-$widthR)/2);
                            $starty = round(($geometryThumb['height']-$heightR)/2);
                            $newImage->cropImage($widthR, $heightR, $startx, $starty);
                        }
                    }
                    $newImage = self::compress($newImage);
                    $newImage->writeImage($DIR.$widthR.'x'.$heightR.'/'.$image[$name_param]);
                    $newImage->destroy();
                    $newImage = new \Imagick($DIR.$folder_origin.'/'.$image[$name_param]);
                }
            }
        }else{
            $newImage->cropImage($width, $height, $x1, $y1);
            $ratio = explode("x", $ratio);
            $newImage->transformImageColorspace(\Imagick::COLORSPACE_RGB);
            $newImage->thumbnailImage($ratio[0], $ratio[1], false, false);
            $newImage = self::compress($newImage);

            $newImage->writeImage($DIR.$ratio[0].'x'.$ratio[1].'/'.$image[$name_param]);
        }
        return 'ok';
    }

    /**
     * @param ActiveRecord $model
     * @param string $name_param
     * @param array $skip_dop
     * @return string result
     * @throws NotFoundHttpException if the Imagick cannot be found
     */
    public function Reset($model, $name_param = 'file_name', $skip_dop=[]){
        if(!class_exists('\Imagick')){
            throw new NotFoundHttpException('Imagick не найден.');
        }
        $image = $model->attributes;
        if(!empty($image)){

            $folder_origin = 'original';
            $skip = array('.', '..', $folder_origin);
            $skip = array_merge($skip, $skip_dop);
            $scan = scandir($model->DIR());
            foreach($scan as $resolution) {
                if(!in_array($resolution, $skip) && file_exists($model->DIR().$resolution.'/'.$image[$name_param])){
                    @unlink($model->DIR().$resolution.'/'.$image[$name_param]);
                }
            }
            if(!empty($skip_dop)){
                $resolution = $skip_dop[0];
                $newImage = new \Imagick($model->DIR().$resolution.'/'.$image[$name_param]);
                $height = explode('x', $resolution);
                $width = $height[0];
                $height = $height[1];

                $geometry = $newImage->getImageGeometry();
                if($geometry['width'] > $width){
                    $newImage->thumbnailImage($width, null );
                    $geometry = $newImage->getImageGeometry();
                }
                if($geometry['height'] > $height){
                    $newImage->thumbnailImage(null, $height );
                }
                $newImage = self::compress($newImage);
                $newImage->writeImage($model->DIR().$width.'x'.$height.'/'.$image[$name_param]);
            }
            return 'ok';
        }else
            return 'err';
    }

    public function Delete($model, $id_name_ph='id_photo', $name_param = 'file_name', $id_parent_name = ''){
        if(!class_exists('\Imagick')){
            throw new NotFoundHttpException('Imagick не найден.');
        }
        $image = $model->attributes;
        if(empty($model))
            throw new NotFoundHttpException('Фотография не найдена.');
        $skip = array('.', '..');
        $files = scandir($model->DIR());
        foreach($files as $file) {
            if(!in_array($file, $skip) && file_exists($model->DIR().$file.'/'.$image['file_name'])){
                //echo($file . '<br />');
                @unlink($model->DIR().$file.'/'.$image[$name_param]);
            }
        }
        Yii::$app->db->createCommand()->delete($model->tableName(), $id_name_ph.' = :id')
            ->bindValues([':id'=>$model->$id_name_ph])->execute();
        return $image;
    }

    public function CreateThumb($post, $table, $id_name_ph, $DIR, $skip_dop=array()){
        $x1 = round($post['x1']);//0
        $y1 = round($post['y1']);//1
        $x2 = round($post['x2']);//504
        $y2 = round($post['y2']);//337
        $id = $_POST['id'];
        $ratio = $_POST['r'];
        $width = $x2-$x1;//504
        $height= $y2-$y1;//336
        $SQL = "SELECT * FROM $table WHERE $id_name_ph = :id";
        $image = Yii::app()->db->createCommand($SQL)->queryRow(true, array(':id'=>$id));

        $quality    = Yii::app()->params['jpeg_quality'];
        $newImage = new \Imagick($DIR.'big/'.$image['file_name']);
        $geometry = $newImage->getImageGeometry();
        if($ratio == 0){
            $skip = array('.', '..', 'big');
            $skip = array_merge($skip, $skip_dop);
            $scan = scandir($DIR);
            foreach($scan as $key=>$resolution) {
                if(!in_array($resolution, $skip)){
                    $heightR = explode('x', $resolution);
                    $widthR = $heightR[0];
                    $heightR = $heightR[1]; //необходимый итоговый размер
                    // начало новой обрезки
                    $ratioCoeff =  $width/$height; // коэффициент выделенной части
                    $ratioCoeffR = $widthR/$heightR;  // коэффициент итогового размера
                    if($ratioCoeffR > $ratioCoeff){ // расчет что и как обрезать
                        $heightCrop = $height;
                        $widthCrop = ceil($heightCrop * $ratioCoeffR);
                        $x1Crop = ceil($x1 - ($widthCrop-$width)/2);
                        $y1Crop = $y1;
                    }else{
                        $widthCrop = $width;
                        $heightCrop = ceil($widthCrop/$ratioCoeffR);
                        $x1Crop = $x1;
                        $y1Crop = ceil($y1 - ($heightCrop-$height)/2);
                    }
                    if($x1Crop >= 0 && $y1Crop >= 0 && ($x1Crop+$widthCrop) <= $geometry['width'] && ($y1Crop+$heightCrop) <= $geometry['height']){
                        $newImage->cropImage((int)$widthCrop, (int)$heightCrop, (int)$x1Crop, (int)$y1Crop);
                        $newImage->thumbnailImage($widthR, $heightR, false, false);
                    }else{
                        // конец новой обрезки
                        $newImage->cropImage($width, $height, $x1, $y1);
                        if(($widthR/$heightR) < ($width/$height)){
                            $w = ceil($heightR * ($width/$height));
                            $newImage->thumbnailImage($w, $heightR, false, false);
                            $geometryThumb = $newImage->getImageGeometry();
                            $startx = round(($geometryThumb['width']-$widthR)/2);
                            $starty = round(($geometryThumb['height']-$heightR)/2);
                            $newImage->cropImage($widthR, $heightR, $startx, $starty);
                        }else{
                            $h = ceil($widthR/($width/$height));
                            $newImage->thumbnailImage($widthR, $h, false, false);

                            $geometryThumb = $newImage->getImageGeometry();
                            $startx = round(($geometryThumb['width']-$widthR)/2);
                            $starty = round(($geometryThumb['height']-$heightR)/2);
                            $newImage->cropImage($widthR, $heightR, $startx, $starty);
                        }
                    }
                    $newImage = self::compress($newImage);
                    $newImage->writeImage($DIR.$widthR.'x'.$heightR.'/'.$image['file_name']);
                    $newImage->destroy();
                    $newImage = new \Imagick($DIR.'big/'.$image['file_name']);

                }
            }
        }else{
            $newImage->cropImage($width, $height, $x1, $y1);
            $ratio = explode("x", $ratio);
            $newImage->transformImageColorspace(\Imagick::COLORSPACE_RGB);
            $newImage->thumbnailImage($ratio[0], $ratio[1], false, false);
            $newImage = self::compress($newImage);
            $newImage->writeImage($DIR.$ratio[0].'x'.$ratio[1].'/'.$image['file_name']);
        }
        return 'ok';
    }

    public function ResetPhoto($table, $id_name_ph, $id, $DIR, $skip_dop=array()){
        $SQL = "SELECT * FROM $table WHERE $id_name_ph = :id";
        $image = Yii::app()->db->createCommand($SQL)->queryRow(true, array(':id'=>$id));
        if(!empty($image)){
            $skip = array('.', '..', 'big');
            $skip = array_merge($skip, $skip_dop);
            $scan = scandir($DIR);
            foreach($scan as $resolution) {
                if(!in_array($resolution, $skip) && file_exists($DIR.$resolution.'/'.$image['file_name'])){
                    @unlink($DIR.$resolution.'/'.$image['file_name']);
                }
            }
            if(!empty($skip_dop)){
                $resolution = $skip_dop[0];
                $newImage = new \Imagick($DIR.$resolution.'/'.$image['file_name']);
                $height = explode('x', $resolution);
                $width = $height[0];
                $height = $height[1];

                $geometry = $newImage->getImageGeometry();
                if($geometry['width'] > $width){
                    $newImage->thumbnailImage($width, null );
                    $geometry = $newImage->getImageGeometry();
                }
                if($geometry['height'] > $height){
                    $newImage->thumbnailImage(null, $height );
                }
                $newImage = self::compress($newImage);
                $newImage->writeImage($DIR.$width.'x'.$height.'/'.$image['file_name']);



                /*   $newImage = Yii::app()->ih->load($DIR.'big/'.$image['file_name']);
                   $resolution = $skip_dop[0];
                   $height = explode('x', $resolution);
                   $width = $height[0];
                   $height = $height[1];
                   if($newImage->width > $width)
                        $newImage->resize($width,false);
                   if($newImage->height > $height)
                       $newImage->resize(false,$height);
                    $quality    = Yii::app()->params['jpeg_quality'];
                    $newImage->save($DIR.$width.'x'.$height.'/'.$image['file_name'],false,$quality); */
            }
            return 'ok';
        }else
            return 'err';
    }


  /*  public function Delete($table, $id_name_ph, $id, $DIR, $id_parent_name = ''){
        $SQL = "SELECT * FROM $table WHERE $id_name_ph = :id";
        $image = Yii::app()->db->createCommand($SQL)->queryRow(true, array(':id'=>$id));
        if(empty($image))
            throw new CHttpException(500);

        if($image['is_main'] == 1 && $id_parent_name != ''){
            $SQL = "SELECT * FROM $table WHERE $id_parent_name = :id AND is_main = 0 ORDER BY priority";
            $image_ismain = Yii::app()->db->createCommand($SQL)->queryRow(true, array(':id'=>$image[$id_parent_name]));
            Yii::app()->db->createCommand()->update($table,array('is_main'=>1),"$id_name_ph = :id",array(':id'=>$image_ismain[$id_name_ph]));
        }
        $skip = array('.', '..');
        $files = scandir($DIR);
        foreach($files as $file) {
            if(!in_array($file, $skip) && file_exists($DIR.$file.'/'.$image['file_name'])){
                //echo($file . '<br />');
                @unlink($DIR.$file.'/'.$image['file_name']);
            }
        }
        $log = new FormLog;
        $log->log($table,$id,'del');
        Yii::app()->db->createCommand()->delete($table, $id_name_ph.' = :id', array(':id'=>$id));
        return $image;
    } */

    public static function Resolutions($DIR){
        $skip = array('.', '..', 'big');
        $scan = scandir($DIR);
        foreach($scan as $key=>$resolution) {
            if(!in_array($resolution, $skip)){
                $key_for_sort = explode('x',$resolution);
                $key_for_sort = $key_for_sort[0].$key;
                $resolutions[$key_for_sort] = $resolution;
            }
        }
        krsort($resolutions);
        foreach($resolutions AS $resolution){
            $tempArr[] = $resolution;
        }
        return $tempArr;
    }
}