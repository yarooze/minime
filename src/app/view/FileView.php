<?php
namespace app\view;

use app\core\HttpCache as HttpCache;

/**
 *
 * @author jb
 */
Class FileView extends MinimeBaseView
{
  protected $template_name = 'File';

  protected function prepareHeaders()
  {
    //$this->headers[] = 'Cache-Control: no-cache, must-revalidate';
    //$this->headers[] = 'Expires: Mon, 26 Jul 1997 05:00:00 GMT';

    //$this->headers[] = 'Expires: '.gmdate('D, d M Y H:i:s', (time()+604800)) . ' GMT';
    //$this->headers[] = 'Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT';
    //$this->headers[] = 'Content-Type: image/png';

    //     header('Expires: Thu, 01-Jan-70 00:00:01 GMT');
    //     header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    //     header('Cache-Control: no-store, no-cache, must-revalidate');
    //     header('Cache-Control: post-check=0, pre-check=0', false);
    //     header('Pragma: no-cache');
    //
    //     $timestamp = time();
    //     $tsstring = gmdate('D, d M Y H:i:s ', $timestamp) . 'GMT';
    //     $etag = md5($timestamp);
    //     header("Last-Modified: $tsstring");
    //     header("ETag: \"{$etag}\"");
    //     header('Expires: Thu, 01-Jan-70 00:00:01 GMT');
    //parent::prepareHeaders();
  }

  public function render($params) {
    $filename = $params['filename'];
    $lastModifiedTimestamp = filemtime($filename);
    $maxAge = 604800;
    HttpCache::init($lastModifiedTimestamp, $maxAge);
    $this->sendHeaders();
    parent::render($params);
  }
}