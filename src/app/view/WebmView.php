<?php
namespace app\view;

/**
 *
 * @author jb
 */
Class WebmView extends FileView
{
  protected $template_name = 'File';

  protected function prepareHeaders()
  {
    //$this->headers[] = 'Cache-Control: no-cache, must-revalidate';
    //$this->headers[] = 'Expires: Mon, 26 Jul 1997 05:00:00 GMT';
    $this->headers[] = 'Content-Type: video/webm';
  }

  public function render($params) {
    parent::render($params);
  }
}