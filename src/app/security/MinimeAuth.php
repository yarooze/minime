<?php
namespace app\security;

/**
 *
 * @author jb
 */
Abstract Class MinimeAuth
{
    const IS_AUTHENTICATED_FALSE        = 0;
    const IS_AUTHENTICATED_ANONYMOUSLY  = 1;
    const IS_AUTHENTICATED_FULLY        = 2;

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

