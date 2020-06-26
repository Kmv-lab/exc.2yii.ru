<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/reset.css',
        //'css/fonts.css',
        'css/style.css',
        //'css/ptg.css',
        //'libs/sumoselect/sumoselect.min.css',
        'libs/owl/assets/owl.carousel.min.css',
        'libs/owl/assets/owl.theme.default.min.css',
        'libs/fancybox/jquery.fancybox.min.css',
        //'css/normalize.css',
        //'css/normalize.css',
    ];
    public $js = [
        //'libs/owl/owl.carousel.min.js',
        '/assets/ab2eca81/jquery.js',
        'libs/inputmask/jquery.inputmask.min.js',
        //'libs/sumoselect/jquery.sumoselect.min.js',
        'libs/fancybox/jquery.fancybox.min.js',
        //'js/script.js',
        'js/vendors.min.js',
        'js/scripts.min.js'
    ];
    public $depends = [
        //'yii\web\YiiAsset',
         //'yii\bootstrap\BootstrapAsset',
    ];
}
