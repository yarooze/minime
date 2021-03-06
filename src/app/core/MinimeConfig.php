<?php
namespace app\core;

/**
 *
 * @author jb
 *
 */
use app\exception\MinimeException;

class MinimeConfig
{
  /**
   * @var \app\Application
   */
  protected $app = null;

  /**
   * default config
   * @var array
   */
  protected $cfg =  array(
    'charset' => 'utf-8',
    'env'     => 'prod',
  );

  public function __construct(\app\Application $app)
  {
    $this->app = $app;
  }

  /**
   * retrieves data from config (or default values)
   * @param string $name
   * @throws MinimeException
   * @return mixed
   */
  public function get($name, $default = null)
  {
    if(array_key_exists($name, $this->app->cfg))
    {
      return $this->app->cfg[$name];
    }
    elseif($default !== null)
    {
      return $default;
    }
    else
    {
      throw new MinimeException('Unknown config parameter! ['. $name . ']');
    }
  }

  /**
   *
   * @param string $name
   * @param mixed  $value
   */
  public function set($name, $value)
  {
    $this->app->cfg[$name] = $value;
  }
}