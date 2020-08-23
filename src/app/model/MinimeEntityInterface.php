<?php

namespace app\model;


use app\Application;

/**
 * Interface for Entities
 *
 *
 * @author jb
 *
 */
Interface MinimeEntityInterface
{

    /**
     * Retur value of Id or Ids of the entity
     * @return mixed
     */
    public function getId();

    /**
     * @param $f_name
     * @return mixed
     */
    public function getMappingByFieldName($f_name);

    /**
     * @param $entity
     * @return mixed
     */
    public function getMapping();

}

