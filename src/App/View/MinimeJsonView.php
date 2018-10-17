<?php
namespace App\View;

// require_once __DIR__.'/BaseView.php';

/**
 *
 * @author jb
 */
Class MinimeJsonView extends BaseView
{
  protected $template_name = '/../Templates/Json.tpl.php';

  protected function prepareHeaders()
  {
    global $timecheck_noecho;
    $timecheck_noecho = true;
    $this->headers[] = 'Cache-Control: no-cache, must-revalidate';
    $this->headers[] = 'Expires: Mon, 26 Jul 1997 05:00:00 GMT';
    $this->headers[] = 'content-type: application/json; charset='.$this->app->config->get('charset');
  }

  public function render($params) {
    if ($this->app->request->getParameter('jsonp')) {
      $params['jsonp']     = $this->app->request->getParameter('jsonp');
      $this->template_name = '/../Templates/Jsonp.tpl.php';
    }
    parent::render($params);
  }
}