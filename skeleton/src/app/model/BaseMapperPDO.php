<?php

namespace app\model;

use \app\core\MinimePDO as PDO;

/**
 * Base class for data base records
 *
 *
 * @author jb
 *
 */
abstract class BaseMapperPDO extends BaseMapper
{
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
     * @param EntityInterface $entity
     * @return true | array('err1', 'err2')
     */
    public function save($entity)
    {
        $validate = $this->validate($entity);
        if (!empty($validate)) {
            return $validate;
        }
        try {
            if ($this->getId($entity) > 0) {
                $this->update($entity);
            } else {
                $this->insert($entity);
            }
        } catch (\Exception $e) {
            $this->db->app->logger->log(array(date('Y.m.d H:i:s'), $e->getMessage(), $e));
            return array('ENTITY_WAS_NOT_SAVED');
        }

        return true;
    }

    protected function generateUniqueArgName($name, &$args) {
        if (array_key_exists(':' . $name, $args)) {
            $suffix = count($args);
            $name .= '_'.$suffix;
        }
        return $name;
    }

    protected function buildQueryCondition ($r_name, $r_value, $dbfield, &$q_where, &$args) {
        if ($r_value === null || $r_value === 'IS NULL') {
            $q_where .= ' ' . $dbfield . ' IS NULL ';
        } elseif (preg_match('/^LIKE(%?[^%]+%?)$/', $r_value, $like)) {
            $key = $this->generateUniqueArgName($r_name, $args);
            $args[':' . $key] = $like[1];
            $q_where .= ' ' . $dbfield . ' LIKE :' . $key . ' ';
        } elseif (preg_match('/^((<=)|(>=)|(<>)|(=)|(<)|(>))(.+)$/', $r_value, $matches)) {
            $key = $this->generateUniqueArgName($r_name, $args);
            $args[':' . $key] = $matches[2];
            $q_where .= ' ' . $dbfield . ' ' . $matches[1]. ' :' . $key . ' ';
        } else {
            $key = $this->generateUniqueArgName($r_name, $args);
            $args[':' . $key] = $r_value;
            $q_where .= ' ' . $dbfield . ' = :' . $key . ' ';
//                    $q_where .= ' ' . $this->alias . '.' . $r_name . ' = :' . $r_name . ' ';
        }
    }

    protected function buildInCondition($r_name, $r_value, $dbfield, &$q_where, &$args) {
        //$q_where .= ' ' . $this->alias . '.' . $r_name . ' IN ( ';
        $q_where .= ' ' . $dbfield . ' IN ( ';
        foreach ($r_value as $in_key => $in_value) {
            $key = $this->generateUniqueArgName($r_name . '_' . $in_key, $args);
            $args[':' . $key] = $in_value;
            if ($in_key > 0) {
                $q_where .= ' , ';
            }
            $q_where .= ' :' . $key . ' ';
        }
        $q_where .= ' ) ';
    }

    protected function buildQueryConditions($filter, &$q_where, &$args, $glue = 'AND') {
        $first_loop = true;
        foreach ($filter as $r_name => $r_value) {
            if (in_array($r_name, array('AND', 'OR'), true)) {
                $glue = $r_name;
                $q_where .= ($first_loop) ? '' : ' ' . $glue . ' ';
                $q_where .= ' ( ';
                $this->buildQueryConditions($r_value, $q_where, $args, $glue);

            } else {
                $dbfield = $r_name;
                $mapping = $this->getMappingByFieldName($r_name);

                if ($mapping && isset($mapping['dbfield'])) {
                    $dbfield = $mapping['dbfield'];
                }

                $q_where .= ($first_loop) ? '' : ' '.$glue.' ';
                $q_where .= ' ( ';
                if (is_array($r_value)) {
                    $first_key = key($r_value);
                    if(is_numeric($first_key) && !is_array($r_value[$first_key])) {
                        $this->buildInCondition($r_name, $r_value, $dbfield, $q_where, $args);
                    } else {
                        $this->buildQueryConditions($r_value, $q_where, $args, $glue);
                    }
                } else {
                    $this->buildQueryCondition($r_name, $r_value, $dbfield, $q_where, $args);
                }
            }

            $q_where .= ' ) ';
            $first_loop = false;
        }
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
    public function retrieveCollection(&$params = array(), $asEntites = false)
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

        //$all_pages = 0;

        $args = array();
        $q_where = '';
        if (is_array($filter)) {
            $this->buildQueryConditions($filter, $q_where, $args);
        }
        if ($q_where) {
            $q_where = ' WHERE ' . $q_where;
        }

        $q_group = '';
        foreach ($group as $key => $field) {
            $q_group .= (empty($q_group)) ? ' GROUP BY ' : ' , ';
            $q_group .= str_replace("'", '`', $this->getConnection()->quote($field)) . ' ';
        }

        $q_order = '';
        foreach ($order as $key => $field) {
            $q_order .= (empty($q_order)) ? ' ORDER BY ' : ' , ';
            $q_order .= str_replace("'", '`', $this->getConnection()->quote($field['name']));
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

    //echo "<pre>";
    //print_r(array('q', $q, $args));
    //print_r(array('q_cnt', $q_cnt, $args));
    //echo "</pre>";

        //
        $all_rows_cnt = 0;
        $res = $this->getConnection()->fetchWithStatement($q_cnt, $args, false);
        if ($res) {
            $all_rows_cnt = $res['cnt'];
        }

        $all_pages = (int)ceil($all_rows_cnt / $limit_2);

        //retrieve rows
        $rows = $this->getConnection()->fetchWithStatement($q, $args);

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
        $args = array($id);

        if ($this->getConnection()->executeStatement($q, $args)) {
            $data = $this->getConnection()->fetch(false, \PDO::FETCH_ASSOC);
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
        $args = array($value);

        if ($this->getConnection()->executeStatement($q, $args)) {
            $data = $this->getConnection()->fetch(false, \PDO::FETCH_ASSOC);
        }

        return $data;
    }

    /**
     * to delete row with given id (overwrite it in child class if needed)
     */
    public function delete($entity)
    {
        $q = 'DELETE
        FROM `' . $this->tablename . '`
        WHERE '. $this->id_field . ' = :id;';
//        $stmt = $this->getConnection()->prepareStatement($q);
//        $stmt->execute(array(':id' => $entity->getId()));
        $args = array(':id' => $entity->getId());
        return false !== $this->getConnection()->executeStatement($q, $args);
    }

    /**
     * actually the workaround. for the case $this->getConnection()->lastinsertedid() doesn't work property
     * @return mixed $lastid
     */
    public function getLastInsertId()
    {
        $lastid = null;
        $q = 'select last_insert_id()';
        $args = array();
        if ($this->getConnection()->executeStatement($q, $args)) {
            $result = $this->getConnection()->fetch(false, \PDO::FETCH_ASSOC);
            $lastid = $result['last_insert_id()'];
            $this->getConnection()->closeCursor();
        }

//        $stmt = $this->getConnection()->prepareStatement('select last_insert_id()');
//        $stmt->execute();
//        if ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
//            $lastid = $result['last_insert_id()'];
//        }
        return $lastid;
    }
}

