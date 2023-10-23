<?php
namespace app\assets;

use yii\web\AssetBundle;
use Yii;

class BlueimpFileUploadAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';

    public $css = [
    ];

    public $cssOptions = [
        'type' => 'text/css',
    ];

    public $js = [
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
