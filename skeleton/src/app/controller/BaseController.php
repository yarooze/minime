<?php
namespace app\controller;

/**
 *
 * @author jb
 */
Class BaseController extends MinimeController
{
  /**
   * @var \app\Application
   */
  protected $app = null;

  public function __construct(\app\Application $app)
  {
     parent::__construct($app);    
  }
}
