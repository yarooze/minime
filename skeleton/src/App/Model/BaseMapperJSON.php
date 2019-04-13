<?php

namespace App\Model;


/**
 * Base class for data base records
 *
 *
 * @author jb
 *
 */
abstract class BaseMapperJSON extends BaseMapper
{

    /**
     * saves MemberConveration into data base (inserts, if new or updates, if exists)
     * @param bool $testCompulsoryFields - test if compulsory fields set, before try to save data
     * @throws RuntimeException
     * @return bool
     */
    public function save($testCompulsoryFields = true)
    {

    }

    /**
     * retrieves multiple data rows
     *
     * @param &array   $params
     *                   'filter' => array('FIELD_MAME' => 'FIELD_VALUE') OR
     *                               array('FIELD_MAME' => array('FIELD_VALUE_1', 'FIELD_VALUE_2')) OR
     *                                   retrieve all
     *                   'page'      - default 1
     *                   'limit'     - default 100
     *                   'orderby'   - array(array('name' => 'NAME', 'order' => 'ASC|DESC'))
     *                   'groupby'   - array('name1', 'name2',..)
     *                   'all_pages' - returns last page number
     *                   'all_rows'  - returns rows number
     * @return array
     */
    public function retrieveCollection(&$params = array())
    {

    }

    /**
     * retrieves data by id
     * Overwrite it if you have other id fields in your model
     * @param int $id
     * @return Ambigous <NULL, mixed>
     */
    public function retrieveById($id)
    {

    }

    /**
     * retrieves data by field
     * @param $field
     * @param $value
     * @return Ambigous <NULL, mixed>
     */
    public function retrieveOneBy($field, $value)
    {

    }

    /**
     * to delete row with given id (overwrite it in child class if needed)
     */
    public function delete()
    {

    }

    /**
     * Inserts Object data into data base
     */
    abstract protected function insert();

    /**
     * saves data into existing data base row
     */
    abstract protected function update();

    /**
     * actually the workaround. for the case $this->getConnection()->lastinsertedid() doesn't work property
     * @return mixed $lastid
     */
    public function getLastInsertId()
    {

    }
}

