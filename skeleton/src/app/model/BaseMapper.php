<?php

namespace App\Model;


/**
 * Base class for data base records
 *
 *
 * @author jb
 *
 */
abstract class BaseMapper implements MapperInterface
{
    /**
     * db and table names and table alias for the queries
     * @var string
     */
    protected $tablename = null;
    protected $alias = 'c';
    protected $id_field = 'id';

    /**
     * @var
     */
    protected $connection;

    /**
     * Fields mapping. NB! Overwrite in the child class
     *
     * @var array
     * e.g.
     * 'fieldname1' => [ // lower case & removed underscores
     *   'getset' => 'FieldName1', // $this->getFieldName1() / $this->setFieldName1()
     *   'field' => 'fieldName1',  // $this->fieldName1
     *   'dbfield' => 'field_name1', // 'SELECT `field_name1`'
     * ]
     */
    protected $mapping = array();

    /**
     * @return mixed
     */
    public function getId($entity)
    {
        $id_field = $this->id_field;
        $mapping = $this->getMappingByFieldName($id_field);
        if ($mapping === null) {
            throw new \RuntimeException('No mapping for the field [' . $id_field . ']!');
        }
        $getter = 'set' . $mapping['getset'];
        return $entity->$getter();
    }

    /**
     *
     * @param array $params - parameter to create/retrieve data
     * @param  $con
     */
    public function __construct($app, $con = null)
    {
        $this->setConnection($con);
        $this->app = $app;
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    /**
     * Test is entity is valid
     * @return null | array('err1', 'err2')
     */
    abstract public function validate($entity);

    /**
     * saves MemberConveration into data base (inserts, if new or updates, if exists)
     * @param bool $testCompulsoryFields - test if compulsory fields set, before try to save data
     * @return true|array - ['msg' => 'err text']
     */
    abstract public function save($entity);

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
    abstract public function retrieveCollection(&$params = array(), $asEntites = false);

    /**
     * retrieves data by id
     * Overwrite it if you have other id fields in your model
     * @param int|array $id
     * @return Ambigous <NULL, mixed>
     */
    abstract public function retrieveById($id);

    /**
     * retrieves data by field
     * @param $field
     * @param $value
     * @return Ambigous <NULL, mixed>
     */
    abstract public function retrieveOneBy($field, $value);

    /**
     * to delete row with given id (overwrite it in child class if needed)
     */
    abstract public function delete($entity);

    /**
     * Inserts Object data into data base
     */
    abstract protected function insert($entity);

    /**
     * saves data into existing data base row
     */
    abstract protected function update($entity);

    /**
     * to fill fields fast. (Be aware! There is no validation by default here!)
     * @param array $values ('fieldname' => 'value')
     *        with setters:'fieldname' --> setFieldname('value')
     *        without setters: $this->$fieldname = $value
     * @param bool $use_setters
     */
    public function setFieldsFromArray($values, $entity, $use_setters = true)
    {
        if (!is_array($values)) {
            return;
        }
        foreach ($values as $f_name => $f_value) {
            $mapping = $this->getMappingByFieldName($f_name);
            if ($mapping === null) {
                //throw new \RuntimeException('No mapping for the field [' . $f_name . ']!');
                continue;
            }
            if ($use_setters) {
                $setter = 'set' . $mapping['getset'];
                $entity->$setter($f_value);
            } else {
                $f_name = $mapping['field'];
                $entity->$f_name = $f_value;
            }
        }
    }

    public function getMappingByFieldName($f_name) {
        $normalized = strtolower(str_replace('_', '', $f_name));
        $mapping = isset($this->mapping[$normalized]) ? $this->mapping[$normalized] : null;
        return $mapping;
    }

    /**
     *
     * @return mysql_db
     */
    protected function getConnection()
    {
        if (empty($this->connection)) {
            //$this->setDb(new PDO(__CLASS__));
        }
        //$this->db->switchCharset(PDO::CHARSET_UTF8, PDO::COLLATE_UTF8MB4_UNICODE_CI);
        return $this->connection;
    }

    /**
     *
     * @param $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * actually the workaround. for the case $this->getConnection()->lastinsertedid() doesn't work property
     * @return mixed $lastid
     */
    abstract public function getLastInsertId();
}

