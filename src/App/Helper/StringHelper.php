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

if(!function_exists('fixUTF8Fields'))
{
  /**
   * fixes utf8 text fields of the record|array before output recursively
   * @param  array|record $record
   * @return array|record
   */
  function fixUTF8Fields($record)
  {
    if(empty($record))
    {
      return $record;
    }
    $data = array();
    foreach($record as $field_name => $field)
    {
      if(is_array($field) || is_object($field))
      {
        $data[$field_name] = fixUTF8Fields($field);
      }
      elseif(is_string($field))
      {
        $data[$field_name] = fixUTF8($field);
      }
      else
      {
        $data[$field_name] = $field;
      }
    }
    return $data;
  }
}

if(!function_exists('getAV'))
{
  /**
   * clean way to get array value
   *
   * Returns the value of the array's member or standard value if the
   * array key wasn't set
   * @param  array $array
   * @param  mixed(int/String) $key
   * @param  mixed $standard (default null)
   * @return mixed
   */
  function getAV(array $array, $key, $standard = null)
  {
    return key_exists($key, $array)?$array[$key]:$standard;
  }
}