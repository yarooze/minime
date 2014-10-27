<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/yarooze/twittee/src/Twittee/Container.php';

$cfg            = require __DIR__.'/../config/config_app.php';
$cfg['routing'] = require __DIR__.'/../config/routing.php';

require __DIR__.'/app.php';

return $app;

