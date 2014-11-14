<?php
namespace App\Controller;

/**
 *
 * @author jb
 */
Class BaseController extends MinimeController
{
  /**
   * @var \App\Application
   */
  protected $app = null;

  public function __construct(\App\Application $app)
  {
     parent::__construct($app);    
  }
}
