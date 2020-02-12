<?php
/**
 * EXAMPLE!!!
 *
 * If you have to use it internal...
 *
 * include this file in your project and call getMiniApp() to access the application
 */

/**
 * @return \app\Application
 */
if(!function_exists('getMiniApp'))
{
  function getMiniApp()
  {
    return require __DIR__.'/bootstrap.php';
  }
}