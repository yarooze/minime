<?php

/**
 * twittee container
 *
 * @see http://twittee.org/
 */

namespace App\Core;

class Container
{
  protected $s=array();
  function __set($k, $c) { $this->s[$k]=$c; }
  function __get($k) { return $this->s[$k]($this); }
}