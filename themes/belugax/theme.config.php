<?php

return [
    'extends' => 'bootstrap3',
    'css' => [
        'jquery.qtip.min.css',
        'uikit.min.css',
        'compiled.css',
        'belugino.css',
        'belugax.css',
    ],
    'js' => [
        'jquery.qtip.min.js',
        'uikit.min.js',
        'belugax.js',
    ],
    'mixins' => [
        'belugaconfig',
        'delivery',
        'libraries',
        'searchkeys',
        'dependentworks',
//        'daia',
        'recorddriver',
        'belugadefault',
    ],
    "less" => [
        "active" => false,
        "compiled.less"
    ],
];