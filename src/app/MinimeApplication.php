<?php

namespace App;

use App\Core\Container as Container,
    App\Exception\MinimeException as MinimeException,
    App\Exception\UnknownRouteException as UnknownRouteException;

/**
 *
 * @author jb
 *
 */
class MinimeApplication extends Container implements ApplicationInterfece
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
        $root_dir = $this->config->get('APP_ROOT_DIR');

        $class = null;
        $file = $root_dir.'/src/App/'.$path.$classname.'.php';
        if(!is_file($file))
        {
            $file = __DIR__.$path.$classname.'.php';
        }
        if(!is_file($file))
        {
            throw new MinimeException('Class ['.$namespace.''.$classname.' in '.$path.'] not found!');
        }
        require_once $file;

        $classname = $namespace.$classname;
        $class     = new $classname($this);

        return $class;
    }

  /**
   * Loads helper functions
   * @param string $helpername
   */
  public function loadHelper($helpername) 
  {
    $root_dir = $this->config->get('APP_ROOT_DIR');

    $file = $root_dir.$helpername.'.php';

    if(!is_file($file))
    {
        $file = __DIR__.'/Helper/'.$helpername.'.php';
    }
    if(!is_file($file))
    {      
        throw new MinimeException('Helper ['.$helpername.' in '.__DIR__.'/Helper/'.'] not found!');
    }

    require_once __DIR__.'/Helper/'.$helpername.'.php';    
  }
}
