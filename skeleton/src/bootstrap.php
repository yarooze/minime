<?php

require_once __DIR__.'/../vendor/autoload.php';

$cfg            = require __DIR__.'/../config/config_app.inc.php';
$cfg['routing'] = require __DIR__.'/../config/routing.inc.php';
$cfg['APP_ROOT_DIR'] = __DIR__.'/../';

require __DIR__.'/app.php';

return $app;

