<?php
namespace app\view;

/**
 *
 * @author jb
 */
Class JpegView extends FileView
{
  protected $template_name = 'Pico';

  protected function prepareHeaders()
  {
    //$this->headers[] = 'Cache-Control: no-cache, must-revalidate';
    //$this->headers[] = 'Expires: Mon, 26 Jul 1997 05:00:00 GMT';
    //$this->headers[] = 'Expires: '.gmdate('D, d M Y H:i:s', (time()+604800)) . ' GMT';
    $this->headers[] = 'Content-Type: image/jpeg';
  }

  public function render($params) {
    parent::render($params);
  }
}