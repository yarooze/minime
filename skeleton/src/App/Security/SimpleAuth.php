<?php
namespace App\Security;

require_once __DIR__.'/BaseAuth.php';

/**
 *
 * @author jb
 */
Class SimpleAuth extends BaseAuth
{
  public function __construct(\App\Application $app)
  {
    $this->app = $app;
  }

  public function isAuthenticated()
  {
    //@todo do your auth stuff here...
    return true;
  }
}