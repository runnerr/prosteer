<?php

error_reporting(E_ALL);

try {

    $config = include APP_PATH . "/app/config/config.php";
    include APP_PATH . "/app/config/loader.php";
    include APP_PATH . "/app/config/services.php";

    $application = new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
