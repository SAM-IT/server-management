<?php

namespace app\commands;

use yii\helpers\Console;

class PamController extends Controller
{
    public $file;

    public function actionFilter()
    {
        $lines = file($this->file, FILE_SKIP_EMPTY_LINES + FILE_IGNORE_NEW_LINES);
        $result = [];
        $time = time();

        foreach($lines as $line) {
          if (strpos($line, '#time=') === false) {
               $result[] = $line;
               continue;
          }
          list($access, $timestamp) = explode('#time=', $line);
          if ($time - $timestamp > 24 * 3600) {
              echo "Removing line: $line, expired.\n";
              continue;
          } else {
              echo "Keeping: $line.\n";
              $result[] = $line;
          }

      }
        file_put_contents($this->file, implode("\n", $result) . "\n");
    }

    public function actionAdd()
    {
        $lines = file($this->file, FILE_SKIP_EMPTY_LINES + FILE_IGNORE_NEW_LINES);
        // Add the new IP.
        $user = getenv('PAM_USER');
        $ip = getenv('PAM_RHOST');
        $time = time();
        array_unshift($lines, "+ : $user : $ip #time=$time");
        file_put_contents($this->file, implode("\n", $lines) . "\n");
    }
}