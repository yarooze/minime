<?php

namespace app\db;

use app\exception\MinimePDOException;

class MinimePDO implements MinimeConnectionInterface
{

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
    protected $collate  = 'utf8_unicode_ci';

    protected $stmt     = null;
    /**
     * Rows after query
     *
     * @var int
     */
    protected $rowscount;

    public $app;

    protected $statements = [];

    /**
     *
     * @param Application $app
     */
    public function __construct($app, $cfg = null) {
        $this->app = $app;
        if (empty($cfg)) {
            $cfg =  $app->config->get('db');
        }
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
    public function switchCharset($enc = self::CHARSET_UTF8, $collate = self::COLLATE_UTF8_UNICODE_CI)
    {
//        if(in_array($enc, array(self::CHARSET_LAT1, self::CHARSET_UTF8)))
//        {
//            $this->getConnection()->exec('set names '.$enc.';');
//        }
        $this->getConnection()->exec('SET NAMES '.$enc.' COLLATE '.$collate.';');
    }

    public function getLastInsertId() {
        return $this->getConnection()->lastInsertId();
    }

    // Direct Query
    /**
     * use simple query without prepared statements
     *
     * @param string $query
     *
     * @return this
     */
    public function query($query) {
        $stmt = $this->getConnection()->query($query, \PDO::FETCH_ASSOC);
        return ($stmt) ? $stmt->fetchAll() : false;
    }

    /**
     * @param $query
     * @return int affected rows
     */
    public function exec($query) {
        return $this->getConnection()->exec($query);
        //$count = $dbh->exec("DELETE FROM fruit");
    }

    // prepared statements
    /**
     * Step 1.
     * Prepare statement (or take prepared) and execute it
     * use $this->fetch to fetch result
     *
     * @param string $q
     * @param array $args
     * @return \PDOStatement|false
     */
    public function executeStatement($q, $args) {
        $this->prepareStatement($q);
        return ($this->stmt->execute($args)) ? $this->stmt : false;
    }

    /**
     * Step 2
     * Fetch data from the statement prepared with $this->executeStatement
     *
     * @param bool $all
     * @param int $fetch_style
     * @param bool $close_cursor
     * @return mixed
     */
    public function fetch($all = true, $fetch_style = \PDO::FETCH_ASSOC, $close_cursor = true) {
        $this->rowscount = $this->stmt->rowCount();
        if($all) {
            $res = $this->stmt->fetchAll($fetch_style);
        } else {
            $res = $this->stmt->fetch($fetch_style);
        }
        if ($close_cursor) {
            $this->stmt->closeCursor();
        }
        return $res;
    }

    /**
     * Prepares statement and fetches the result.
     *
     * @param string $q - sql query string
     * @param array $args - query parameters array(':foo' => 'bar')
     * @param bool $all - fetch one or multiple rows
     * @param int $fetch_style - \PDO::FETCH_*
     * @param bool $close_cursor - close cursor after execution
     * @return mixed
     */
    public function fetchWithStatement(
        $q, $args, $all = true, $fetch_style = \PDO::FETCH_ASSOC, $close_cursor = true
    ) {
        $result = array();
        if ($this->executeStatement($q, $args)) {
            $result = $this->fetch($all, $fetch_style, $close_cursor);
        }

        return $result;
    }

    /**
     *
     * @param string $q  with parameter like :name, :id
     * @return PDO statement
     */
    protected function prepareStatement($q) {
        $stmtHash = md5($q);
        if (!isset($this->statements[$stmtHash])) {
            try {
                $this->statements[$stmtHash] = array(
                    'q' => $q,
                    'stmt' => $this->getConnection()->prepare($q),
                );
            } catch (\Exception $e) {
                $this->app->logger->log(array($e, $q));
                throw new MinimePDOException($e->getMessage());
            } 
        }
        /** @var \PDOStatement $stmt */
        $this->stmt = $this->statements[$stmtHash]['stmt'];

        return $this->stmt;
    }

    /**
     * Close cursor for the executed statement
     */
    public function closeCursor() {
        if ($this->stmt) {
            $this->stmt->closeCursor();
        }
    }

//   $stmt = $db->prepare("SELECT * FROM table WHERE id=:id AND name=:name");
//   $stmt->bindValue(':id', $id, PDO::PARAM_INT);
//   $stmt->bindValue(':name', $name, PDO::PARAM_STR);
//   $stmt->execute();
//   $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

//    /**
//     * execute query with prepared statement
//     *
//     * @param string $query  with parameter like :name, :id
//     * @param array  $vals  array(':name' => $name, ':id' => $id)
//     * @return this
//     */
//    public function stmt($query, $vals) {
//        $this->prepareStatement($query)->execute($vals);
//        return $this;
//    }

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