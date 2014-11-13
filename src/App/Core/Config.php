<?php
namespace App\Core;

/**
 *
 * @author jb
 *
 */
use App\Exception\MinimeException;

class Config
{
  /**
   * @var \App\Application
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

  public function __construct(\App\Application $app)
  {
    $this->app = $app;
  }

  /**
   * retrieves data from config (or default values)
   * @param string $name
   * @throws MinimeException
   * @return mixed
   */
  public function get($name)
  {
    if(array_key_exists($name, $this->app->cfg))
    {
      return $this->app->cfg[$name];
    }
    elseif(array_key_exists($name, $this->cfg))
    {
      return $this->cfg[$name];
    }
    else
    {
      throw new MinimeException('Unknown config parameter!');
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