<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      /*  'css/site.css',*/
        'css/font-awesome.min.css',
//        'css/app.min.css',
    ];
/*
    public $js = [
        'js/popper.min.js',
        'js/app.min.js',
    ];
*/
    public $depends = [
        'yii\web\YiiAsset',
       'yii\bootstrap\BootstrapAsset',
    ];
}
