<?php


namespace app\commands;

use yii\helpers\Console;

class Controller extends \yii\console\Controller
{

    protected function exec($command, $options = [], $accepts = []) {
        $final = $command . $this->createOptions($options) . ' 2>&1';
        $this->stdout("Executing command:\n", Console::FG_YELLOW);
        $this->stdout($final . "\n", Console::FG_CYAN);

        exec($final, $output, $result);
        if ($result > 0) {
            // Try matching
            foreach ($accepts as $accept) {
                if (preg_match($accept, implode("\n", $output))) {
                    $this->stdout(implode("\n", $output) . "\n", Console::FG_YELLOW);
                    $this->stdout("Output acceptable. [OK]\n", Console::FG_GREEN);
                    return;
                }
            }
            $this->stderr(implode("\n", $output) . "\n", Console::FG_RED);

        } else {
            $this->stdout(implode("\n", $output) . "\n", Console::FG_GREEN);
            $this->stdout("Status success. [OK]\n", Console::FG_GREEN);
        }
    }
    protected function createOptions(array $options)
    {
        $result = '';
        foreach($options as $key => $value) {
            if (is_numeric($key)) {
                $result .= " $value";
            } elseif ($value === true) {
                $result .= " --$key";
            } elseif (isset($value)) {
                $result .= " --$key $value";

            }
        }
        return $result;
    }

}