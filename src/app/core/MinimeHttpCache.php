<?php
namespace app\core;

if (!function_exists('getallheaders'))
{
  function getallheaders()
  {
    $headers = '';
    foreach ($_SERVER as $name => $value)
    {
      if (substr($name, 0, 5) == 'HTTP_')
      {
        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
      }
    }
    return $headers;
  }
}

/**
 *
 * @author Jasper  http://stackoverflow.com/questions/1971721/how-to-use-http-cache-headers-with-php
 *
 */


class MinimeHttpCache
{
  public static function init($lastModifiedTimestamp, $maxAge)
  {
    if (self::isModifiedSince($lastModifiedTimestamp))
    {
      self::setLastModifiedHeader($lastModifiedTimestamp, $maxAge);
    }
    else
    {
      self::setNotModifiedHeader($maxAge);
    }
  }

  private static function isModifiedSince($lastModifiedTimestamp)
  {
    $allHeaders = getallheaders();

    if (array_key_exists("If-Modified-Since", $allHeaders))
    {
      $gmtSinceDate = $allHeaders["If-Modified-Since"];
      $sinceTimestamp = strtotime($gmtSinceDate);

      // Can the browser get it from the cache?
      if ($sinceTimestamp != false && $lastModifiedTimestamp <= $sinceTimestamp)
      {
        return false;
      }
    }

    return true;
  }

  private static function setNotModifiedHeader($maxAge)
  {
    // Set headers
    header("HTTP/1.1 304 Not Modified", true);
    header("Cache-Control: public, max-age=$maxAge", true);
    die();
  }

  private static function setLastModifiedHeader($lastModifiedTimestamp, $maxAge)
  {
    // Fetching the last modified time of the file
    $date = gmdate("D, j M Y H:i:s", $lastModifiedTimestamp)." GMT";
    // Set headers
    header("HTTP/1.1 200 OK", true);
    header("Cache-Control: public, max-age=$maxAge", true);
    header("Last-Modified: $date", true);
  }
}