<?php
namespace App\Model;

require_once __DIR__.'/ExampleModelInterface.php';

/**
 *
 * @author jb
 */
Class ExampleModel implements ExampleModelpInterface
{
  public function __construct()
  {
    //whatever you want to do here... for example: connect to the database
  }

  /**
   *
   * @see ExampleModelInterface
   */
  public function getSomeStuffById($id)
  {
    return array('id' => $id, 'data' => 'stuff');
  }
}