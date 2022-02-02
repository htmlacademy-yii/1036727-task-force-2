<?php

namespace app\assets;

use yii\web\AssetBundle;

class ModalFormAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $css = [
        'css/modal-form.css'
    ];

    public $js = [
        'js/modal-form.js',
    ];
}
