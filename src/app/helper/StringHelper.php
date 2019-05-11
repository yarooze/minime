<?php
/**
 * Helpers to work with strings
 */

if(!function_exists('fixUTF8'))
{
  /**
   *
   * @param string $text
   * @return string
   */
  function fixUTF8($text)
  {
    $enc = (mb_detect_encoding($text,"UTF-8, ISO-8859-1",true)) ?: 'ISO-8859-1';
    return (isUTF8($text)) ? (string)$text : iconv($enc, "UTF-8", (string)$text);
  }
}

if(!function_exists('isUTF8'))
{
  /**
   *
   * @param string $string
   * @return boolean
   */
  function isUTF8($string)
  {
    return (utf8_encode(utf8_decode($string)) == $string);
  }
}
