<?php

set_time_limit(0);

define('ROOT_DIR', __DIR__.'/..');
define('SRC_DIR', ROOT_DIR.'/src');

define('ENV_PRODUCTION', 'production');

require_once ROOT_DIR.'/vendor/autoload.php';
$app = new Silex\Application();

$env = getenv('APP_ENV') ?: ENV_PRODUCTION;

if ($env != ENV_PRODUCTION) {
    Symfony\Component\Debug\Debug::enable();
}

require SRC_DIR.'/app.php';
require SRC_DIR.'/services.php';

$application = $app['console'];
$application->add(new app\commands\RandomizeCommand());
$application->add(new app\commands\SendCommand());
$application->run();