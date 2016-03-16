<?php

namespace app\commands;

use yii\helpers\Console;

class PamController extends Controller
{
    public $file = '/etc/security/access-known-ips.conf';

    /**
     * Remove all known ip entries older than 24hours from the known ips file.
     *
     */
    public function actionFilter()
    {
        $lines = file_exists($this->file) ? file($this->file, FILE_SKIP_EMPTY_LINES + FILE_IGNORE_NEW_LINES) : [];
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

        if (file_exists($this->file) || !empty($result)) {
            file_put_contents($this->file, implode("\n", $result) . "\n");
        }

    }

    /**
     * Add a user / IP combination to the known ips file. Uses PAM_USER and PAM_RHOST environment variables.
     */
    public function actionAdd()
    {
        $lines = file_exists($this->file) ? file($this->file, FILE_SKIP_EMPTY_LINES + FILE_IGNORE_NEW_LINES) : [];
        // Add the new IP.
        $user = getenv('PAM_USER');
        $ip = getenv('PAM_RHOST');
        if (!empty($user)) {
            $time = time();
            echo "Adding: $user @ $ip\n";
            passthru('env');
            array_unshift($lines, "+ : $user : $ip #time=$time");
            if (file_exists($this->file) || !empty($lines)) {
                file_put_contents($this->file, implode("\n", $lines) . "\n");
            }
        }

    }

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'file'
        ]);
    }
}