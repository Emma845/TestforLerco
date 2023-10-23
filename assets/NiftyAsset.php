<?php
namespace app\assets;

use yii\web\AssetBundle;
use Yii;

class NiftyAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        //'//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700',
        'css/open-sans.css',
        'css/nifty.min.css',
        'css/demo/nifty-demo-icons.min.css',
        'css/nifty_icon_premium/line-icons/premium-line-icons.min.css',
    ];

    public $cssOptions = [
        'type' => 'text/css',
    ];

    public $js = [
        'js/nifty.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\BootstrapSelectAsset',
        'app\assets\PaceAsset',
        'app\assets\FontAwesomeAsset',
        'app\assets\MdiAsset',
        'app\assets\MagicCheckAsset',
        'app\assets\AnimateAsset',
    ];
}
