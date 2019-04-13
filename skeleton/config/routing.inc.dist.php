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
 *       'credentials'     => array(),           // route credentials:
 *       // empry - blocked, '*' - free, 'NAME' - must have (e.g. 'ADMIN', 'USER', 'AUTHENTICATED_FULLY', or whatever)
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
            'credentials'     => array('AUTHENTICATED_FULLY'),
      ),
  //
      'MyRegister' =>
      array('url'        => array('register'),
          'methods'    => array('get'),
          'formats'    => array('html'),
          'controller' => 'ExampleController',
          'action'     => 'defaultAction',
          'credentials'     => array('*'),
      ),
      'MyRegisterSubmit' =>
      array('url'        => array('register','submit'),
          'methods'    => array('post'),
          'formats'    => array('html'),
          'controller' => 'ExampleController',
          'action'     => 'mySubmitAction',
          'credentials'     => array('*'),
      ),
      'MyRegisterSuccess' =>
      array('url'        => array('register','success'),
          'methods'    => array('get'),
          'formats'    => array('html'),
          'controller' => 'ExampleController',
          'action'     => 'myRegisterSuccessAction',
          'credentials'     => array('*'),
      ),
  // CRUD
//    '{crud}List' =>
//        array('url' => array('{crud}'),
//            'methods' => array("get"),
//            'formats' => array('html'),
//            'controller' => '{crud}Controller',
//            'action' => 'listAction',
//            'credentials'     => array('AUTHENTICATED_FULLY'),
//        ),
//    '{crud}Edit' =>
//        array('url' => array('{crud}', 'edit', array('name' => 'id', 'regexp' => '\d+')),
//            'methods' => array("get", "post"),
//            'formats' => array('html'),
//            'controller' => '{crud}Controller',
//            'action' => 'editAction',
//            'credentials'     => array('AUTHENTICATED_FULLY'),
//        ),
//    '{crud}View' =>
//        array('url' => array('{crud}', array('name' => 'id', 'regexp' => '\d+')),
//            'methods' => array("get", "post"),
//            'formats' => array('html'),
//            'controller' => '{crud}Controller',
//            'action' => 'viewAction',
//            'credentials'     => array('AUTHENTICATED_FULLY'),
//        ),
//    '{crud}Delete' =>
//        array('url' => array('{crud}', 'delete', array('name' => 'id', 'regexp' => '\d+')),
//            'methods' => array("post"),
//            'formats' => array('html'),
//            'controller' => '{crud}Controller',
//            'action' => 'deleteAction',
//            'credentials'     => array('AUTHENTICATED_FULLY'),
//        ),
  //
      'default' =>
      array('url'     => array(),
            'methods' => array(),
            'formats' => array(),
            'controller' => 'ExampleController',
            'action'  => 'defaultAction',
            'credentials'     => array('*'),
      ),
  //
);
