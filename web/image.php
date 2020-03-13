<?php
$border = true;
$color = 'white';

$url_img = $_SERVER["REQUEST_URI"];
if(preg_match('/.+\/$/', $url_img))
    $url_img = substr($url_img, 0, -1);
$url_img = explode('?', $url_img);
$url_img = $url_img[0];
$typeImage = explode('.', $url_img);
$typeImage = $typeImage[count($typeImage)-1];
if(!file_exists($_SERVER['DOCUMENT_ROOT'].$url_img)){
    $url_arr = explode('/', substr($url_img, 1));
    $big_img = $_SERVER['DOCUMENT_ROOT'].'/'.$url_arr[0].'/'.$url_arr[1].'/original/'.$url_arr[3];
    if(!file_exists($big_img))
        header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
    $whArr = explode('x', $url_arr[2]);
    $path_folder = $_SERVER['DOCUMENT_ROOT'].'/'.$url_arr[0].'/'.$url_arr[1].'/'.(int)$whArr[0].'x'.(int)$whArr[1].'/';
    if(file_exists($big_img) && file_exists($path_folder)){
        $newImage = new \Imagick($big_img);
        $width = $whArr[0];
        $height = $whArr[1];
        $geometry = $newImage->getImageGeometry();
        if($border){
            if($geometry['width'] > $width){
                $newImage->thumbnailImage($width, null );
                $geometry = $newImage->getImageGeometry();
            }
            if($geometry['height'] > $height){
                $newImage->thumbnailImage(null, $height );
                $geometry = $newImage->getImageGeometry();
            }
            $x = ( $width - $geometry['width'] ) / 2;
            $y = ( $height - $geometry['height'] ) / 2;
            $newImage->borderImage($color, $x, $y);
        }else{
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
            $newImage->transformImageColorspace(imagick::COLORSPACE_RGB);
            $newImage->cropImage($width_new, $height_new, $startx, $starty);
            $newImage->thumbnailImage($width, $height, false, false);
        }
        $newImage->stripImage();
        if($newImage->getImageMimeType() == 'image/jpeg'){
            $newImage->setSamplingFactors(array('2x2', '1x1', '1x1'));
            $newImage->setImageCompressionQuality(85);
            $img = $newImage->getImageBlob();
            if(floor(strlen($img)/1024) > 10){
                $newImage->setInterlaceScheme(Imagick::INTERLACE_PLANE);
            }
        }
        $newImage->writeImage($path_folder.$url_arr[3]);
        header('Content-Type: image/'.$typeImage);
        echo $newImage;
    }else
        header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
}
?>