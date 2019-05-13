<?php
namespace app\view;

/**
 *
 * @author jb
 */
Class PngView extends FileView
{
  protected $template_name = 'Pico';

  protected function prepareHeaders()
  {
    //$this->headers[] = 'Cache-Control: no-cache, must-revalidate';
    //$this->headers[] = 'Expires: Mon, 26 Jul 1997 05:00:00 GMT';
    $this->headers[] = 'Content-Type: image/png';
  }

  public function render($params) {
    parent::render($params);
  }
}