<?php
require_once __DIR__.'/App/Application.php';

require_once __DIR__.'/App/Helper/StringHelper.php';

use App\Application as Application,
    App\Model\ExampleModel as ExampleModel;

$app = new Application(); // Create the application

$app->cfg     = function ($app) use ($cfg) { return $cfg; };

$config = $app->loadClass('Config', '\App\Core\\', '/Core/');
$app->config  = function ($app) use ($config) { return $config; };

$logger = $app->loadClass('Logger', '\App\Core\\', '/Core/');
$app->logger  = function ($app) use ($logger) { return $logger; };
$app->logger->setLogDir(__DIR__.'/../log');

// add for data base
//$pdo = $app->loadClass('PDO', '\App\Core\\', '/Core/');
//$app->db = function ($app) use ($pdo) { return $pdo; };
$dbFactory = new \App\DB\DBFactory($app);
$app->dbFactory = function ($app) use ($dbFactory) { return $dbFactory; };

// add for i18n
//$i18n = $app->loadClass('I18n', '\App\Core\\', '/Core/');
//$app->i18n = function ($app) use ($i18n) { return $i18n; };

//we dont need this stuff for 'internal usage'
if($app->config->get('env') != 'internal')
{
  $session = $app->loadClass('Session', '\App\Core\\', '/Core/');
  $app->session  = function ($app) use ($session) { return $session; };

  $auth = $app->loadClass('SimpleAuth', '\App\Security\\', '/Security/');
  $app->auth = function ($app) use ($auth) { return $auth; };

  $request = $app->loadClass('Request', '\App\Core\\', '/Core/');
  $app->request = function ($app) use ($request) { return $request; };

  $router = $app->loadClass('Router', '\App\Core\\', '/Core/');
  $app->router  = function ($app) use ($router) { return $router; };
}
if($app->config->get('env') == 'internal')
{
  //make your internal stuff here or ignore this place
  //require_once __DIR__.'/App/Model/ExampleModel.php';
  //$examplemodel = new ExampleModel();
  //$app->examplemodel  = function ($app) use ($examplemodel) { return $examplemodel; };
}
