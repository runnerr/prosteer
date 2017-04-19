<?php
error_reporting(E_ALL);
define('VERSION', '1.0.0');
chdir(__DIR__);
ini_set('memory_limit', '-1');

use Phalcon\Cli\Console as ConsoleApp;

$config = include __DIR__ . "/config/config.php";
include APP_PATH . "/app/config/loader.php";
include APP_PATH . "/app/config/services.php";

$console = new ConsoleApp();
$console->setDI($di);

$arguments = [];
foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments["task"] = $arg;
    } elseif ($k === 2) {
        $arguments["action"] = $arg;
    } elseif ($k >= 3) {
        $arguments["params"][] = $arg;
    }
}

$di->setShared("console", $console);

define('CURRENT_TASK',   (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

if ($fp = fopen(APP_PATH . '/app/cache/cli.log','a')) {
    $line = date("Y-m-d H:i:s")."\t".CURRENT_TASK."\t".CURRENT_ACTION."\n";
    fputs($fp, $line);
    fclose($fp);
}

try {
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}