<?php

namespace App\Model;

interface ExampleModelInterface
{
  /**
   *
   * @param int $id
   * @return Ambigous <number, NULL, array>
   */
  public function getSomeStuffById($id);
}