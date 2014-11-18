<?php
namespace App\Core;

/**
 *
 * @author jb
 *
 */
use App\Exception\MinimeException;

class MinimeSession
{
  /**
   * @var \App\Application
   */
  protected $app = null;

  protected $id = null;

  public function __construct(\App\Application $app)
  {
    if (!isset($_SESSION)) {session_start();}
    $this->app = $app;
    $this->id = session_id();
  }

  public function getSessionId() {
    return $this->id;
  }

  /**
   * retrieves data from the $_SESSION
   * @param  string $name
   * @throws MinimeException
   * @return mixed
   */
  public function get($name, $default = null)
  {
    if(array_key_exists($name, $_SESSION))
    {
      return $_SESSION[$name];
    }
    else
    {
      return $default;
      //throw new MinimeException('Unknown session parameter!');
    }
  }

  /**
   *
   * @param string $name
   * @param mixed  $value
   */
  public function set($name, $value)
  {
    $_SESSION[$name] = $value;
  }
}