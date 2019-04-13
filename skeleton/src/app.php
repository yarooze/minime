<?php

namespace App;

$app = new Application(); // Create the application

//$app->loadHelper('StringHelper');
//$app->loadHelper('ArrayHelper');

$app->cfg     = function ($app) use ($cfg) { return $cfg; };

$config = new \App\Core\Config($app);
$app->config  = function ($app) use ($config) { return $config; };

$logger = new \App\Core\Logger($app);
$app->logger  = function ($app) use ($logger) { return $logger; };
$app->logger->setLogDir(__DIR__.'/../log');

// add for data base
//$pdo = new \App\Core\PDO($app);
//$app->db = function ($app) use ($pdo) { return $pdo; };
$dbFactory = new \App\DB\DBFactory($app);
$app->dbFactory = function ($app) use ($dbFactory) { return $dbFactory; };

// add for i18n
//$i18n = $app->loadClass('I18n', '\App\Core\\', '/Core/');
$i18n = new \App\Core\I18n($app);
$app->i18n = function ($app) use ($i18n) { return $i18n; };

//we dont need this stuff for 'internal usage'
if($app->config->get('env') != 'internal')
{
  $session = new \App\Core\Session($app);
  $app->session  = function ($app) use ($session) { return $session; };

  $auth = new \App\Security\SimpleAuth($app);
  $app->auth = function ($app) use ($auth) { return $auth; };

  $request = new \App\Core\Request($app);
  $app->request = function ($app) use ($request) { return $request; };

  $router = new \App\Core\Router($app);
  $app->router  = function ($app) use ($router) { return $router; };
}
if($app->config->get('env') == 'internal')
{
  //make your internal stuff here or ignore this place
  //$examplemodel = new ExampleModel();
  //$app->examplemodel  = function ($app) use ($examplemodel) { return $examplemodel; };
}
