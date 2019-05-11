<?php
namespace app\controller;

/**
 *
 * @author jb
 */
Class MinimeController
{
  /**
   * @var \App\Application
   */
  protected $app = null;

  public function __construct(\App\Application $app)
  {
    $this->app = $app;
  }

  protected function renderView($view, $data = array()) {
      $view->render($data);
  }
}
