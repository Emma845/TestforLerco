<?php
namespace app\assets;

use yii\web\AssetBundle;
use Yii;

class WaypointsAsset extends AssetBundle
{
    public $sourcePath = '@bower/waypoints/lib';

    public $css = [
    ];

    public $js = [
        'jquery.waypoints.min.js',
    ];

    public $depends = [
    ];
}
