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
  protected $dbname    = null;
  protected $tablename = null;
  protected $alias     = 'c';

  /**
   *
   * @param array $params  - parameter to create/retrieve data
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
   * saves MemberConveration into data base (inserts, if new or updates, if exists)
   * @param bool $testCompulsoryFields - test if compulsory fields set, before try to save data
   * @throws RuntimeException
   */
  public function save($testCompulsoryFields = true)
  {
    if($testCompulsoryFields && !$this->canBeSaved())
    {
      throw new \RuntimeException('Not all compulsory field were set!');
    }
    if($this->getId() > 0)
    {
      $this->update();
    } else {
      $this->insert();
    }
    //and update $this in your save method
  }

  /**
   * retrieves multiple data rows
   *
   * @param &array   $params
   *                   'filter' => array('FIELD_MAME' => 'FIELD_VALUE') OR retrieve all
   *                   'page'      - default 1
   *                   'limit'     - default 100
   *                   'orderby'   - array(array('name' => 'NAME', 'order' => 'ASC|DESC'))
   *                   'all_pages' - returns last page number
   * @return array
   */
  public function retriveCollection(&$params = array())
  {
    $filter = isset($params['filter']) ? $params['filter'] : array();
    //params stuff
    $page    = isset($params['page']) ? (int)$params['page'] : 1;
    $limit_2 = (isset($params['limit']) && $params['limit'] > 0) ? (int)$params['limit'] : 100;
    $limit_1 = ($page) ? (($page-1)*$limit_2) : 0;

    $order   = (isset($params['orderby']) && !empty($params['orderby'])) ? $params['orderby'] : array(array('name'=>' id', 'order'=>'DESC'));

    $all_pages = 0;

    $args = array();
    $q_where = '';
    if(is_array($filter))
    {
      foreach($filter as $r_name => $r_value)
      {
        $args[':'.$r_name] = $r_value;
        $q_where = (empty($q_where)) ? '' : ' AND ';
        $q_where .= ' '.$this->alias.'.'.$r_name.' = :'.$r_name.' ';
      }
    }
    if($q_where)
    {
      $q_where = ' WHERE '.$q_where;
    }

    $q_order = '';
    foreach($order as $key => $field)
    {
      $q_order .= (empty($q_order)) ? ' ORDER BY ' : ' , ';
      $q_order .= $field['name'];
      $q_order .= (strtoupper($field['order']) === 'DESC') ? ' DESC ': ' ASC ';
    }

    $q = 'SELECT
 '.$this->alias.'.*
 FROM `'.$this->dbname.'`.`'.$this->tablename.'` AS '.$this->alias.' ';
    $q .= $q_where;
    $q .= $q_order;
    $q .= ' LIMIT '.$limit_1.','.$limit_2.';';

    //count messages
    $q_cnt = 'SELECT
count(*) AS cnt
FROM `'.$this->dbname.'`.`'.$this->tablename.'` AS '.$this->alias.' ';
    $q_cnt .= $q_where . ';';

    //count messages
    $stmt = $this->getDb()->prepareStatement($q_cnt);
    $all_data_cnt = 0;
    if ($stmt->execute($args))
    {
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
      $all_data_cnt = $res['cnt'];
    }
    $all_pages = (int)ceil($all_data_cnt / $limit_2);

    //retrieve messages
    $stmt = $this->getDb()->prepareStatement($q);
    $data = array();
    if ($stmt->execute($args))
    {
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $params['all_pages'] = $all_pages;

    return $data;
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
          '.$this->alias.'.*
          FROM `'.$this->dbname.'`.`'.$this->tablename.'` AS '.$this->alias.'
          WHERE '.$this->alias.'.id = ?
          LIMIT 1';
    $stmt = $this->getDb()->prepareStatement($q);
    if ($stmt->execute(array($id)))
    {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return $data;
  }

  /**
   * to delete row with given id (overwrite it in child class if needed)
   */
  public function delete()
  {
      $q = 'DELETE
        FROM `'.$this->dbname.'`.`'.$this->tablename.'` AS '.$this->alias.'
        WHERE '.$this->alias.'.id = :id;';
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
    if(!is_array($values))
    {
      return;
    }
    foreach($values as $f_name => $f_value)
    {
      if($use_setters)
      {
        $setter = 'set'.ucfirst($f_name);
        $this->$setter($f_value);
      } else {
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
    if(empty($this->db))
    {
      //  TODO add exception?
      //$this->setDb(new PDO(__CLASS__));
    }
    $this->db->switchCharset(PDO::CHARSET_UTF8);
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
    if($result = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $lastid  = $result['last_insert_id()'];
    }
    return $lastid;
  }
}
