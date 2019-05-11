<?php
namespace app\core;

/**
 *
 * @author jb
 *
 */
use app\exception\MinimeException;

class MinimeLogger
{
   /**
    * name of the log file
    *
    * @var string
    */
   const LOG_TYPE_STD   = 'app';


   const LOG_LVL_INFO = 1;
   const LOG_LVL_WARN = 2;
   const LOG_LVL_ERR  = 3;

  /**
   * @var \App\Application
   */
  protected $app = null;

  protected $logDir = '';

  public function __construct(\App\Application $app)
  {
    $this->app = $app;
    //$this->log = $app->cfg['log'];
  }

  public function setLogDir($logDir)
  {
    $this->logDir = $logDir;
  }

  public function log($msg, $type = self::LOG_TYPE_STD, $lvl=self::LOG_LVL_INFO) {

   $env = $this->app->config->get('env');
   if(is_array($msg)) {
     $msg = json_encode($msg);
   } elseif (is_object($msg)) {
     $msg = json_encode((array)$msg);
   } else {
     $msg = json_encode(array($msg));
   }

   $log_path = $this->logDir.'/'.$type.'_'.$env.'.log';
   $log_write_success = file_put_contents($log_path, $msg."\n", FILE_APPEND | LOCK_EX);
  }
}