<?php


namespace App;


interface ApplicationInterfece
{
    public function run();
	
  /**
   * Escapes a text for HTML.
   *
   * @param string  $text         The input text to be escaped
   * @param integer $flags        The flags (@see htmlspecialchars)
   * @param string  $charset      The charset
   * @param Boolean $doubleEncode Whether to try to avoid double escaping or not
   *
   * @return string Escaped text
   */	
	public function escape($text, $flags = ENT_COMPAT, $charset = null, $doubleEncode = true);

	/**
     * very simple autoloader for this project
     * @param string $classname
     * @param string $namespace
     * @param string $path
     * @throws MinimeException
     * @return Object
     */
    public function loadClass($classname, $namespace = '\\', $path = '/');
	
	
  /**
   * Loads helper functions
   * @param string $helpername
   */
  public function loadHelper($helpername);
}