<?php

namespace app;

use app\exception\UnknownRouteException;

/**
 *
 * @author jb
 *
 */
class Application extends MinimeApplication
{

  public function run()
  {
    try {

      $this->router->checkRouteCredentials();

      parent::run();

    } catch (UnknownRouteException $e) {
      if($this->config->get('env') === 'dev') {
          throw new UnknownRouteException($e->getMessage(), $e->getCode(), $e);
      } else {
          $this->router->redirect('default',array());
      }
    }
  }
}
