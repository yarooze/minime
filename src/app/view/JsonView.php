<?php
namespace app\view;

/**
 *
 * @author jb
 */
Class JsonView extends BaseView
{
  protected $template_name = 'Json';

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
      $this->template_name = 'Jsonp';
    }
    parent::render($params);
  }
}