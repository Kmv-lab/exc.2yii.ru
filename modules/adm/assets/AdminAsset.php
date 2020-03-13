<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\adm\assets;

use yii\web\AssetBundle;

/**
 * Класс AdminAsset ресурсы для админки
 * @package app\modules\admin\assets
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'admFiles/css/adm.css',
        'admFiles/js/codemirror/codemirror.css',
        'admFiles/js/codemirror/theme/eclipse.css',
    ];
    public $js = [
        'admFiles/ckeditor/ckeditor.js',
        'admFiles/ckfinder/ckfinder.js',
        'admFiles/js/adm.js',
        'admFiles/js/codemirror/codemirror.js',
        'admFiles/js/codemirror/mode/xml/xml.js',
        'admFiles/js/codemirror/addon/active-line.js',
        'admFiles/js/codemirror/addon/matchtags.js',
        'admFiles/js/codemirror/addon/xml-fold.js',
        'admFiles/js/codemirror/addon/closetag.js',
];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
    ];
}
