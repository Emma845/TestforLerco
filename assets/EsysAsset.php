<?php
namespace app\assets;

use yii\web\AssetBundle;
use Yii;

class EsysAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $js = [
        'js/esystems/beforload.js',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
