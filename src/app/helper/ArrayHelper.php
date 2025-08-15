<?php
/**
 * Helpers to work with arrays
 */

if(!function_exists('fixUTF8Fields'))
{
  require_once __DIR__.'/StringHelper.php';
  if(!function_exists('fixUTF8'))
  {
    throw new MinimeException('Function fixUTF8 not found! Please load StringHelper!');
  }
 
  /**
   * fixes utf8 text fields of the record|array before output recursively
   * @param  mixed array|record $record
   * @return mixed array|record
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
    return array_key_exists($key, $array)?$array[$key]:$standard;
  }
}

if(!function_exists('getByPath'))
{
  /** 
   * @param string path e.g "a.b.c" 
   * @param array arr e.g arr[a][b][c]
   * @param mixed def default value
   * 
   * @return mixed
   */
  function getByPath($path, $arr, $def = null) {
      $res = $arr;
      $index = null;
      $key = null;
      if (is_string($path)) {
          $path = str_replace('[', '.', $path);
          $path = str_replace(']', '', $path);
          $path = explode('.', $path);
      }
      for ($index = 0; $index < count($path); $index++) {
          $key = $path[$index];
          if ($res && isset($res[$key])) {
              $res = $res[$key];
              continue;
          }
          return $def;
      }
      return $res;
  }  
}

if(!function_exists('setByPath'))
{
    /**
     * "a.b[7].d" = "foo" --> "{a: {b: [6 x undefined, {d: "foo"}]}}"
     * @param string|array path e.g "a.b[7].c"|["a","b[","7","c"]
     * @param array $obj e.g obj.a.b.c
     * @param mixed $value
     */
    function setByPath($path, &$arr, $value) {
        if (is_string($path)) {
            // replace array brackets but keep one to differentiate between empty {} and []
          $path = str_replace('[', '[.', $path);
          $path = str_replace(']', '', $path);
          $path = explode('.', $path);
        }
        if (count($path) > 1) {
            $e = array_shift($path);
                $defaultEmpty = array();
            if (strpos($e, '[') !== false) {
              $e = str_replace('[', '', $e);
              $defaultEmpty = array();
            }
            if (!isset($arr[$e]) || !is_array($arr[$e]) ) {
                $arr[$e] = $defaultEmpty;
            }
            setByPath($path, $arr[$e], $value);
        } else {
            $arr[$path[0]] = $value;
        }
    }
}