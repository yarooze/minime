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

if(!function_exists('checkBoxSymbol')) {
    function checkBoxSymbol($value)
    {
        return ($value) ? '&#x2611;' : '&#x2610;';
    }
}

if(!function_exists('slugify')) {
    function slugify($string) {
        $string = transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", $string);
        $string = preg_replace('/[\W]/', '-', $string);
        return trim($string, '-');
    }
}

if(!function_exists('declOfNum')) {
    /**
     * ������� ��������� ������������ � ������� �����
     *
     * @param int    $number ����� ������� ����� �����������
     * @param array  $titles ������ ���� ��� ��������� - array('������', '������', '������')
     * @return string
     **/
    function declOfNum($number, $titles)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
    }
}
