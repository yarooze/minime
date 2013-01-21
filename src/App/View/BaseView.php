<?php
namespace App\View;

/**
 *
 * @author jb
 */
Abstract Class BaseView
{
  /**
   * @var \App\Application
   */
  protected $app = null;

  /**
   *
   * @var string
   */
  protected $template_name = '';

  protected $headers = array();

  public function __construct(\App\Application $app)
  {
    $this->app = $app;
    $this->prepareHeaders();
  }

  protected function prepareHeaders()
  {
    $this->headers[] = 'content-type: text/html; charset='.$this->app->config->get('charset');
  }

  public function sendHeaders()
  {
    foreach ($this->headers as $header)
    {
      header($header);
    }
  }

  public function render($params)
  {
    $this->sendHeaders();

    $app = $this->app;
    extract($params);
    include __DIR__.$this->template_name;
  }
}