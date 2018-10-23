<?php

namespace App\Core;

class MinimePDO {


    /**
     * charsets to use for connection
     * @var string
     */
    const CHARSET_LAT1 = 'latin1';
    const CHARSET_UTF8 = 'utf8';

    const COLLATE_LATIN1_GENERAL_CI = 'latin1_general_ci';
    const COLLATE_UTF8_UNICODE_CI = 'utf8_unicode_ci';
    const COLLATE_UTF8MB4_UNICODE_CI = 'utf8mb4_unicode_ci';

    protected $host     = 'localhost';
    protected $dbname   = null;
    protected $username = null;
    protected $password = null;
    protected $link     = null;
    protected $charset  = 'utf8';

    protected $stmt     = null;
    /**
     * Rows after query
     *
     * @var int
     */
    protected $rowscount;

    /**
     *
     * @param Application $app
     */
    public function __construct($app) {
        $cfg = $app->config->get('db');
        $this->host        = $cfg['host'];
        $this->dbname      = $cfg['dbname'];
        $this->username    = $cfg['username'];
        $this->password    = $cfg['password'];

        if (isset($cfg['charset'])) {
            $this->charset  = $cfg['charset'];
        }
        if (isset($cfg['collate'])) {
            $this->collate  = $cfg['collate'];
        }

        $this->link        = null;
    }

    public function getConnection() {
        if(!$this->link) {
            $this->connect();
        }
        return $this->link;
    }

    public function connect() {
        $this->link = new \PDO('mysql:host='.$this->host.';dbname='.$this->dbname.';charset='.$this->charset,
            $this->username,
            $this->password);
        $this->link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->link->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->switchCharset($this->charset, $this->collate);
    }

    /**
     * to switch between lat1 and utf8
     *
     * @param string $enc     self::CHARSET_*
     * @param string $collate self::COLLATE_*
     */
    public function switchCharset($enc = self::CHARSET_UTF8, $collate = self::COLLATE_UTF8MB4_UNICODE_CI)
    {
        if(in_array($enc, array(self::CHARSET_LAT1, self::CHARSET_UTF8)))
        {
            $this->getConnection()->exec('set names '.$enc.';');
        }
        $this->getConnection()->exec('SET NAMES '.$enc.' COLLATE '.$collate.';');
    }

    public function getLastInsertId() {
        return $this->getConnection()->lastInsertId();
    }

    /**
     *
     * @return array
     */
    public function fetch($all = true, $fetch_style = \PDO::FETCH_ASSOC) {
        $this->rowscount = $this->stmt->rowCount();
        if($all) {
            $res = $this->stmt->fetchAll($fetch_style);
        } else {
            $res = $this->stmt->fetch($fetch_style);
        }
        return $res;
    }

    /**
     * use simple query
     *
     * @param string $query
     *
     * @return this
     */
    public function query($query) {
        $this->stmt = $this->getConnection()->query($query);
        return $this;
    }


    /**
     *
     * @param string $query  with parameter like :name, :id
     * @return PDO statement
     */
    public function prepareStatement($query) {
        $this->stmt = $this->getConnection()->prepare($query);
        return $this->stmt;
    }

    /**
     * execute query with prepared statement
     *
     * @param string $query  with parameter like :name, :id
     * @param array  $vals  array(':name' => $name, ':id' => $id)
     * @return this
     */
    public function stmt($query, $vals) {
        $this->prepareStatement($query)->execute($vals);
        return $this;
    }

//   $stmt = $db->prepare("SELECT * FROM table WHERE id=:id AND name=:name");
//   $stmt->bindValue(':id', $id, PDO::PARAM_INT);
//   $stmt->bindValue(':name', $name, PDO::PARAM_STR);
//   $stmt->execute();
//   $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     *
     * @return
     */
    public function getDbname()
    {
        return $this->dbname;
    }

    /**
     *
     * @param $dbname
     */
    public function setDbname($dbname)
    {
        $this->dbname = $dbname;
    }

    /**
     * Returns a quoted string that is theoretically safe to pass into an SQL statement. Returns FALSE if the driver does not support quoting in this way.
     *
     * @param $string
     * @return mixed
     */
    public function quote($string)
    {
        return $this->getConnection()->quote($string);
    }
}