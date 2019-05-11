<?php
/**
 * Example config file for other developers.
 * You can put default config here, to show it to everyone.
 * The real config is in "config_app.php". You can make a local settings in it.
 */
return array(
  'env' => 'dev',  //'prod' => http, 'incl' => for internal usage
  'charset' => 'utf-8',
   'db' => array(
      'PDO' => array(
        'host'     => 'localhost',
        'dbname'   => '',
        'username' => '',
        'password' => '',
        //'charset' => 'utf8',
        //'collate' => 'utf8_unicode_ci',
      ),
      'JSON' => array(
        'dir' => 'data/DB/JSON/',
      ),
   ),
  'mapping' => array(
    'default' => 'JSON',
    'User' => 'JSON',
    'Example' => 'PDO',
  ),
);
