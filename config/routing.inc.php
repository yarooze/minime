<?php
/**
 * example routing (real routing is in routing.php)
 * usage:
 * array(
 *   $routeName1 => //route name for internal usage. Must be unique.
 *     array(
 *       'url' => array('my','cool','url'),         // how this uri looks like: /my/cool/url
 *       // you can also use regexp like:
 *       // => array('user',array('name'=>'username','regexp'=>'\w'),array('name'=>'id','regexp'=>'\d+')
 *       // /user/billy/666 to make GET parameters look prettier $username = 'billy' and $id = 666
 *       'methods'    => array('post','get','put'), // allowed methods (all if empty)
 *       'controller' => 'myCoolController',        // controller name
 *       'action'     => 'myCoolAction',           // action mane
 *     ),
 * )
 */
return array(
  //
      'getStuffById' =>
      array('url'     => array('get','stuffbyid',array('name'=>'id','regexp'=>'\d+')),
            'methods' => array('get'),
            'formats' => array(),
            'action'  => 'defaultAction',
      ),
  //
      'default' =>
      array('url'     => array(),
            'methods' => array(),
            'formats' => array(),
            'action'  => 'defaultAction',
      ),
  //
);
