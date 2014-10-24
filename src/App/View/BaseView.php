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

  /**
   *
   */
  protected function prepareHeaders()
  {
    $this->headers[] = 'content-type: text/html; charset='.$this->app->config->get('charset');
  }

  /**
   *
   */
  public function sendHeaders()
  {
    foreach ($this->headers as $header)
    {
      header($header);
    }
  }

  /**
   * @param string $string
   * @param boool  $raw
   */
  public function printString($string, $raw = false) {
    if($raw) {
      echo $string;
    } else {
      echo $this->app->escape($string);
    }
  }

  /**
   * renders the partial
   *
   * @param string $partial - partial's name (without "_")
   * @param array  $params  - variables for the partial
   */
  public function renderPartial($partial, $params) {
    $app = $this->app;
    $view = $this;
    extract($params);
    include __DIR__.'/../Templates/_'.$partial.'.tpl.php';
  }

  /**
   * renders the template
   *
   * @param array  $params  - variables for the template
   */
  public function render($params)
  {
    $this->sendHeaders();

    $app = $this->app;
    $view = $this;
    extract($params);
    include __DIR__.$this->template_name;
  }
}