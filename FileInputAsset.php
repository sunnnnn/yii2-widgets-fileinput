<?php
namespace sunnnnn\fileinput;

use yii\web\AssetBundle;

class FileInputAsset extends AssetBundle{ 
    
    public $sourcePath = __DIR__;
    
    public $css = [
        'css/fileinput.min.css'
    ];
    
    public $js = [
        'js/plugins/piexif.min.js',
        'js/plugins/sortable.min.js',
        'js/plugins/purify.min.js',
        'js/plugins/popper.min.js',
        'js/fileinput.min.js',
        'js/locales/zh.js',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
