<?php
namespace app\assets;

use yii\web\AssetBundle;
use Yii;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/esystems/afterload.js',
    ];

    public $css = [
        'css/themes/theme-dark.min.css',
		//'css/esystems/theme.css',
		'css/esystems/helpers.css',
		'css/esystems/ui.css',
		'css/esystems/views.css',
    ];

    public $cssOptions = [
        'type' => 'text/css',
    ];

    public $depends = [
        'app\assets\EsysAsset',
        'app\assets\NiftyAsset',
    ];
}
