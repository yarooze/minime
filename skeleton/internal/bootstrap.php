<?php

$cfg = require __DIR__.'/../config/config_app.php';
$cfg['APP_ROOT_DIR'] = __DIR__.'/../';

$cfg['env'] = 'internal';

require __DIR__.'/../src/app.php';

return $app;
