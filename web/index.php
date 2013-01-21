<?php
try {
  $app = require __DIR__.'/../src/bootstrap.php';
  $app->run();
} catch (Exception $e) {
  if($app->config->get('env') == 'dev')
  {
    throw $e;
  }
  else
  {
    echo $e->getMessage();
  }
}