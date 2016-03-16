<?php

namespace app\commands;

use yii\helpers\Console;

class NginxController extends Controller
{

    public function actionIndex() {

    }
    public function actionEnsure()
    {
        $this->exec('apt-get install', ['-y', 'nginx']);
    }
}