<?php

namespace App\Model;

use \App\Core\MinimePDO as PDO;

/**
 * Base class for data base records
 *
 *
 * @author jb
 *
 */
abstract class BaseDBModel
{
    protected $db = null;

    /**
     * db and table names and table alias for the queries
     * @var string
     */
    protected $tablename = null;
    protected $alias = 'c';
    protected $id_field = 'id';

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
    public function getId()
    {
        $id_field = $this->id_field;
        return $this->$id_field;
    }

    /**
     *
     * @param array $params - parameter to create/retrieve data
     * @param mysql_db $con
     */
    public function __construct($params = array(), $con = null)
    {
        $this->setDb($con);
        $this->init($params);
    }

    public function __destruct()
    {
        $this->db = null;
    }

    /**
     * init your object here. Either retrieve or fill with default data
     * @param array $params
     */
    abstract protected function init($params = array());

    /**
     * It will be called on save(true). Check your stuff here. For example:
     * if all compulsory fields were set, if row already exist, fixed some fields and whatewer...
     * @return bool
     */
    abstract protected function canBeSaved();

    /**
     * Fields to join to the select query
     * e.g.  ', table2.fiel1, table2.field2';
     * @return string
     */
    protected function joinFields()
    {
        return '';
    }

    /**
     * Join tables
     * e.g. ' LEFT JOIN table2 AS t2 ON t1.join_id = t2.id '
     * @return string
     */
    protected function joinQuery()
    {
        return '';
    }

    protected function getIdAsIdQueryPart() {
        $id_as_id = '';
        if ($this->id_field !== 'id') {
            $id_as_id = ' ' . $this->alias . '.' . $this->id_field . ' AS id, ';
        }
        return $id_as_id;
    }

    /**
     * saves MemberConveration into data base (inserts, if new or updates, if exists)
     * @param bool $testCompulsoryFields - test if compulsory fields set, before try to save data
     * @throws RuntimeException
     * @return bool
     */
    public function save($testCompulsoryFields = true)
    {
        if ($testCompulsoryFields && !$this->canBeSaved()) {
            //throw new \RuntimeException('Not all compulsory field were set!');
            return false;
        }
        try {
            if ($this->getId() > 0) {
                $this->update();
            } else {
                $this->insert();
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
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
    public function retriveCollection(&$params = array())
    {
        $filter = isset($params['filter']) ? $params['filter'] : array();
        //params stuff
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $limit_2 = (isset($params['limit']) && $params['limit'] > 0) ? (int)$params['limit'] : 100;
        $limit_1 = ($page) ? (($page - 1) * $limit_2) : 0;

        $group = (isset($params['groupby']) && !empty($params['groupby'])) ?
            $params['groupby'] :
            array();

        $order = (isset($params['orderby']) && !empty($params['orderby'])) ?
            $params['orderby'] :
            array(array('name' => '' . $this->id_field, 'order' => 'DESC'));

        $all_pages = 0;

        $args = array();
        $q_where = '';
        if (is_array($filter)) {
            foreach ($filter as $r_name => $r_value) {
                $q_where .= (empty($q_where)) ? '' : ' AND ';
                if (is_array($r_value)) {
                    $q_where .= ' ' . $this->alias . '.' . $r_name . ' IN ( ';
                    foreach ($r_value as $in_key => $in_value) {
                        $args[':' . $r_name . '_' . $in_key] = $in_value;
                        if ($in_key > 0) {
                            $q_where .= ' , ';
                        }
                        $q_where .= ' :' . $r_name . '_' . $in_key . ' ';
                    }
                    $q_where .= ' ) ';
                } else {
                    $args[':' . $r_name] = $r_value;
                    $q_where .= ' ' . $this->alias . '.' . $r_name . ' = :' . $r_name . ' ';
                }
            }
        }
        if ($q_where) {
            $q_where = ' WHERE ' . $q_where;
        }

        $q_group = '';
        foreach ($group as $key => $field) {
            $q_group .= (empty($q_group)) ? ' GROUP BY ' : ' , ';
            $q_group .= str_replace("'", '`', $this->getDb()->quote($field)) . ' ';
        }

        $q_order = '';
        foreach ($order as $key => $field) {
            $q_order .= (empty($q_order)) ? ' ORDER BY ' : ' , ';
            $q_order .= str_replace("'", '`', $this->getDb()->quote($field['name']));
            $q_order .= (strtoupper($field['order']) === 'DESC') ? ' DESC ' : ' ASC ';
        }

        $q = 'SELECT
 ' . $this->getIdAsIdQueryPart() . '        
 ' . $this->alias . '.* ' . $this->joinFields() . '
 FROM `' . $this->tablename . '` AS ' . $this->alias . ' ';
        $q .= $this->joinQuery();
        $q .= $q_where;
        $q .= $q_group;
        $q .= $q_order;
        $q .= ' LIMIT ' . $limit_1 . ',' . $limit_2 . ';';

        //count rows
        $q_cnt = 'SELECT
count(*) AS cnt
FROM `' . $this->tablename . '` AS ' . $this->alias . ' ';
        $q_cnt .= $q_where . ';';

        $stmt = $this->getDb()->prepareStatement($q_cnt);
        $all_rows_cnt = 0;
        if ($stmt->execute($args)) {
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            $all_rows_cnt = $res['cnt'];
        }
        $all_pages = (int)ceil($all_rows_cnt / $limit_2);

        //retrieve rows
        $stmt = $this->getDb()->prepareStatement($q);
        $rows = array();
        if ($stmt->execute($args)) {
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        $params['all_pages'] = $all_pages;
        $params['all_rows'] = $all_rows_cnt;

        return $rows;
    }

    /**
     * retrieves data by id
     * Overwrite it if you have other id fields in your model
     * @param int $id
     * @return Ambigous <NULL, mixed>
     */
    public function retrieveById($id)
    {
        $data = null;

        $q = 'SELECT
          ' . $this->getIdAsIdQueryPart() . '
          ' . $this->alias . '.* ' . $this->joinFields() . '
          FROM `' . $this->tablename . '` AS ' . $this->alias . '
          ' . $this->joinQuery() . '
          WHERE ' . $this->alias . '.' . $this->id_field . ' = ?
          LIMIT 1';
        $stmt = $this->getDb()->prepareStatement($q);
        if ($stmt->execute(array($id))) {
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        return $data;
    }

    /**
     * retrieves data by field
     * @param $field
     * @param $value
     * @return Ambigous <NULL, mixed>
     */
    public function retrieveOneBy($field, $value)
    {
        $data = null;
        $q = 'SELECT
          ' . $this->getIdAsIdQueryPart() . '
          ' . $this->alias . '.* ' . $this->joinFields() . '
          FROM `' . $this->tablename . '` AS ' . $this->alias . '
          ' . $this->joinQuery() . '
          WHERE ' . $this->alias . '.' . $field . ' = ?
          LIMIT 1';
        $stmt = $this->getDb()->prepareStatement($q);

        if ($stmt->execute(array($value))) {
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        return $data;
    }

    /**
     * to delete row with given id (overwrite it in child class if needed)
     */
    public function delete()
    {
        $q = 'DELETE
        FROM `' . $this->tablename . '`
        WHERE '. $this->id_field . ' = :id;';
        $stmt = $this->getDb()->prepareStatement($q);
        $stmt->execute(array(':id' => $this->getId()));
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
     * to fill fields fast. (Be aware! There is no validation by default here!)
     * @param array $values ('fieldname' => 'value')
     *        with setters:'fieldname' --> setFieldname('value')
     *        without setters: $this->$fieldname = $value
     * @param bool $use_setters
     */
    public function setFieldsFromArray($values, $use_setters = true)
    {
        if (!is_array($values)) {
            return;
        }
        foreach ($values as $f_name => $f_value) {
            $normalized = strtolower(str_replace('_', '', $f_name));
            $mapping = isset($this->mapping[$normalized]) ? $this->mapping[$normalized] : null;
            if ($mapping === null) {
                //throw new \RuntimeException('No mapping for the field [' . $f_name . ']!');
                continue;
            }
            if ($use_setters) {
                $setter = 'set' . $mapping['getset'];
                $this->$setter($f_value);
            } else {
                $f_name = $mapping['field'];
                $this->$f_name = $f_value;
            }
        }
    }

    /**
     *
     * @return mysql_db
     */
    public function getDb()
    {
        if (empty($this->db)) {
            //$this->setDb(new PDO(__CLASS__));
        }
        //$this->db->switchCharset(PDO::CHARSET_UTF8, PDO::COLLATE_UTF8MB4_UNICODE_CI);
        return $this->db;
    }

    /**
     *
     * @param mysql_db $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * actually the workaround. for the case $this->getDb()->lastinsertedid() doesn't work property
     * @return mixed $lastid
     */
    public function getLastInsertId()
    {
        $lastid = null;
        $stmt = $this->getDb()->prepareStatement('select last_insert_id()');
        $stmt->execute();
        if ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $lastid = $result['last_insert_id()'];
        }
        return $lastid;
    }
}

