<?php

namespace App\Model;


use App\Application;

/**
 * Interface for Mappers
 *
 *
 * @author jb
 *
 */
Interface MapperInterface
{

    /**
     * Retur value of Id or Ids of the entity
     * @return mixed
     */
    public function getId($entity);

    /**
     * MapperInterface constructor.
     * @param Application $app
     * @param null $con
     */
    public function __construct($app, $con = null);

    /**
     * saves MemberConveration into data base (inserts, if new or updates, if exists)
     * @param EntityInterface $entity
     * @return true|array - ['msg' => 'err text']
     */
    public function save($entity);

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
     * @param bool $asEntites - populate entities or as array
     * @return array
     */
    public function retrieveCollection(&$params = array(), $asEntites = false);

    /**
     * retrieves data by id
     * Overwrite it if you have other id fields in your model
     * @param int $id
     * @return Ambigous <NULL, mixed>
     */
    public function retrieveById($id);

    /**
     * retrieves data by field
     * @param $field
     * @param $value
     * @return Ambigous <NULL, mixed>
     */
    public function retrieveOneBy($field, $value);

    /**
     * to delete row with given id (overwrite it in child class if needed)
     */
    public function delete($entity);

    /**
     * to fill fields fast. (Be aware! There is no validation by default here!)
     * @param array $values ('fieldname' => 'value')
     *        with setters:'fieldname' --> setFieldname('value')
     *        without setters: $this->$fieldname = $value
     * @param $entity
     * @param bool $use_setters
     */
    public function setFieldsFromArray($values, &$entity);

    /** TODO fix name f->g */
    public function getMappinfByFieldName($f_name);
    
    /**  @return array */
    public function getMapping();

    /**
     *
     * @param $con
     */
    public function setConnection($con);

    /**
     * @return mixed $lastid
     */
    public function getLastInsertId();
}

