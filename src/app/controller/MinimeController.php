<?php
namespace app\controller;

/**
 *
 * @author jb
 */
Class MinimeController
{
  /**
   * @var \app\Application
   */
  protected $app = null;

  public function __construct(\app\Application $app)
  {
    $this->app = $app;
  }

  protected function renderView($view, $data = array()) {
      $view->render($data);
  }
}
