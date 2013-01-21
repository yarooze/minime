<?php
namespace App\Controller;

/**
 *
 * @author jb
 */
Class BaseController
{
  /**
   * @var \App\Application
   */
  protected $app = null;

  public function __construct(\App\Application $app)
  {
    $this->app = $app;
  }
}
