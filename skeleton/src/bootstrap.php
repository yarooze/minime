<?php

$cfg            = require __DIR__.'/../config/config_app.php';
$cfg['routing'] = require __DIR__.'/../config/routing.php';
$cfg['APP_ROOT_DIR'] = __DIR__.'/../';

require __DIR__.'/app.php';

return $app;

