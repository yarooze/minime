<?php
namespace App;

require_once __DIR__.'/Exception/MinimeException.php';
require_once __DIR__.'/Exception/UnknownRouteException.php';
//require_once __DIR__.'/../../vendor/autoload.php';
//require_once __DIR__.'/../../../twittee/src/Twittee/Container.php';

use App\Core\Container as Container,
    App\Exception\MinimeException as MinimeException,
    App\Exception\UnknownRouteException as UnknownRouteException;

/**
 *
 * @author jb
 *
 */
class MinimeApplication extends Container
{
  public function run()
  {
    if(!$this->auth->isAuthenticated())
    {
      throw new MinimeException('Authentication failed!');
    }

    $route_name = $this->router->getCurrentRouteName();
    if($route_name == null)
    {
      throw new UnknownRouteException('Unknown route!');
    }

    $controller = $this->loadClass($this->router->getControllerName(), '\App\Controller\\', '/Controller/');
    $this->controller  = function ($app) use ($controller) {
      return $controller;
    };

    $action = $this->router->getActionName();
    $this->controller->$action();
//     echo '<pre>';
//     print_r($this->router);
//     echo '</pre>';
  }

  /**
   * Escapes a text for HTML.
   *
   * @param string  $text         The input text to be escaped
   * @param integer $flags        The flags (@see htmlspecialchars)
   * @param string  $charset      The charset
   * @param Boolean $doubleEncode Whether to try to avoid double escaping or not
   *
   * @return string Escaped text
   */
  public function escape($text, $flags = ENT_COMPAT, $charset = null, $doubleEncode = true)
  {
    return htmlspecialchars($text, $flags, $charset ?: $this->config->get('charset'), $doubleEncode);
  }

  /**
   * very simple autoloader for this project
   * @param string $classname
   * @param string $namespace
   * @param string $path
   * @throws MinimeException
   * @return Object
   */
  public function loadClass($classname, $namespace = '\\', $path = '/')
  {
    $class = null;
    if(!is_file(__DIR__.$path.$classname.'.php'))
    {
        throw new MinimeException('Class ['.$namespace.''.$classname.' in '.$path.'] not found!');
    }
    require_once __DIR__.$path.$classname.'.php';

    $classname = $namespace.$classname;
    $class     = new $classname($this);

    return $class;
  }
}
