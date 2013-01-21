<?php
namespace App\Security;

/**
 *
 * @author jb
 */
Abstract Class BaseAuth
{
  /**
   * @var \App\Application
   */
  protected $app = null;

  public function __construct(\App\Application $app)
  {
    $this->app = $app;
  }

  /**
   * @return bool
   */
  abstract public function isAuthenticated();
}

