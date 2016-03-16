<?php

define('YII_DEBUG', true);
define('YII_TRACE_LEVEL', 3);
chdir(__FILE__);
call_user_func(function() {

    // Locate autoload.php.
    $dir = __DIR__;
    while ($dir != '/') {
        if (file_exists($dir . "/vendor/autoload.php")) {
            $path = $dir . "/vendor/autoload.php";
            break;
        }
        if ($dir != __DIR__ && file_exists('composer.json')) {
            // Should check composer.json for vendor directory.
        }
        $dir = dirname($dir);
    }
    if (!isset($path)) {
        die("Could not locate autoloader.");
    }
    $loader = require $path;



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
