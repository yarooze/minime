<?php
namespace App;

require_once __DIR__.'/../../vendor/autoload.php';

//require_once __DIR__.'/Exception/MinimeException.php';
//require_once __DIR__.'/../../vendor/autoload.php';
//require_once __DIR__.'/../../vendor/yarooze/twittee/src/Twittee/Container.php';

use Twittee\Container as Container,
    App\Exception\MinimeException as MinimeException;

/**
 *
 * @author jb
 *
 */
class Application extends MinimeApplication
{
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
      return parent::loadClass($classname, $namespace, $path);
    }    
    require_once __DIR__.$path.$classname.'.php';

    $classname = $namespace.$classname;
    $class     = new $classname($this);

    return $class;
  }
}
