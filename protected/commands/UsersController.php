<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

class UsersController extends Controller
{
    /**
     * @var bool Whether to output extended details for debugging purposes.
     */
    public $verbose = false;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [

            'verbose'
        ]);
    }


    public function actionIndex() {
        $this->stdout("Ok\n", Console::FG_RED);
    }
}