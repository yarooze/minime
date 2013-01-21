<?php
namespace App\Controller;

require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../View/DefaultView.php';

use App\View\DefaultView as DefaultView;

/**
 *
 * @author jb
 */
Class DefaultController extends BaseController
{
  public function defaultAction()
  {
    $view = new DefaultView($this->app);

    $data = $this->app->request->getParameters();

    $view->render(array('data' => $data));
  }
}
