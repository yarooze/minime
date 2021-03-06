<?php

namespace app\model;


/**
 * Base class for data base records
 *
 *
 * @author jb
 *
 */
abstract class MinimeMapper implements MinimeMapperInterface
{
    /**
     * db and table names and table alias for the queries
     * @var string
     */
    protected $tablename = null;
    protected $entityname = null;
    protected $alias = 'c';
    protected $id_field = 'id';

    protected $app  = null;

    /**
     * @var
     */
    protected $connection;

    /**
     * DB fields mapping. Additional to the entity mapping. NB! Overwrite in the child class
     *
     * @var array
     * e.g.
     * 'fieldname1' => [ // lower case & removed underscores
     *   'dbfield' => 'field_name1', // 'SELECT `field_name1`'
     * ]
     */
    protected $dbmapping = array();

    public function getIdField()
    {
        return $this->id_field;
    }

    /**
     * @return mixed
     */
    public function getId($entity)
    {
        if (is_array($this->id_field)) {
            $id = arary();
            foreach ($this->id_field as $id_field) {
                $mapping = $this->getMappingByFieldName($id_field, $entity);
                if ($mapping === null) {
                    throw new \RuntimeException('No mapping for the field [' . $id_field . ']!');
                }
                $getter = 'get' . $mapping['getset'];
                
                $id[$id_field] = $entity->$getter();
            } 
        } else {
          $mapping = $this->getMappingByFieldName($this->id_field, $entity);
          if ($mapping === null) {
              throw new \RuntimeException('No mapping for the field [' . $this->id_field . ']!');
          }
          $getter = 'get' . $mapping['getset']; 
          $id = $entity->$getter();
        }

        return $id;
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

    public function createEntity()
    {
        $entittyName =  'app\model\\'.$this->entityname.'Entity';
        return new $entittyName();
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
     * @inheritdoc
     */
    public function setFieldsFromArray($values, &$entity,  $use_setters = true)
    {
        if (!is_array($values)) {
            return;
        }
        foreach ($values as $f_name => $f_value) {
            $mapping = $this->getMappingByFieldName($f_name, $entity);
            if ($mapping === null) {
                //throw new \RuntimeException('No mapping for the field [' . $f_name . ']!');
                continue;
            }
            $setter = 'set' . $mapping['getset'];
            $entity->$setter($f_value);
        }
    }

    public function getMappingByFieldName($f_name, $entity) {
        $mapping = $this->getMapping($entity);
        $normalized = strtolower(str_replace('_', '', $f_name));
        return isset($mapping[$normalized]) ? $mapping[$normalized] : null;
    }
    
    public function getMapping($entity) {
        $mapping = $entity->getMapping();
        foreach ($this->mapping as $key => $value) {
            $mapping[$key] = array_merge($mapping[$key], $value);
        }
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

