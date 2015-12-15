<?php

define('YII_DEBUG', true);
define('YII_TRACE_LEVEL', 3);
call_user_func(function() {

    $loader = require "vendor/autoload.php";
//    var_dump($loader); die();


    class Yii extends \yii\BaseYii {
        public static $loader;
    }

    // Create DI container.
    Yii::$container = new yii\di\Container;

    Yii::$loader = $loader;
    $composer = json_decode(file_get_contents('composer.json'), true);
    $config = include("protected/config/config.php");

    (new \yii\console\Application($config))->run();

});





