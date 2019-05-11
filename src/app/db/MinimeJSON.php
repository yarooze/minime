<?php


namespace App\DB;


class MinimeJSON implements MinimeConnectionInterface
{
    /**
     * @var Application
     */
    public $app;

    /**
     * @var array
     */
    protected $cfg;

    /**
     * @var array
     */
    protected $data = array();

    /**
     *
     * @param Application $app
     */
    public function __construct($app, $cfg = array())
    {
        $this->app = $app;
        $this->cfg = $cfg;
    }

    protected function loadJSON($tablename) {
        $path = $this->app->config->get('APP_ROOT_DIR') . '/' .    $this->cfg['dir'];
        $json_string = file_get_contents($path.'/'.$tablename.'.json');
        $this->data[$tablename] = json_decode($json_string, true);
    }

    public function getTableData($tablename) {
        if (!isset($this->data[$tablename])) {
            $this->loadJSON($tablename);
        }
        $data = $this->data[$tablename];
        return $data;
    }

    public function getRowById ($tablename, $id) {
        $rows = $this->getTableData($tablename);
        return isset($rows[$id]) ? $rows[$id] : null;
    }

    public function getRowBy($tablename, $field, $value) {
        $rows = $this->getTableData($tablename);
        $result = null;
        foreach ($rows as $id => $row ) {
            if (isset($row[$field]) && $row[$field] == $value) {
                $result = $row;
                $result['_id'] = $id;
            }
        }
        return $result;
    }

    /**
     * @param array $query
     *   getData(array(
     *     'limit' => array(1,100), // sql-like (offset, limit)
     *   ))
     * @return array
     */
    public function getData($tablename, $query = array()) {

        $limit = isset($query['limit']) ? $query['limit'] : array(1,25);
        $filter = isset($query['filter']) ? $query['filter'] : array();
        $rows = $this->getTableData($tablename);
        $counter = 0;
        $result = array(
            'total' => 0,
            'data' => array(),
        );
        $data = array();

        foreach ($rows as $id => $row ) {
            // filter
            if (!empty($filter)) {
                $skip = false;
                foreach ($filter as $f_name => $f_value) {
                    if (isset($row[$f_name]) && $row[$f_name] != $f_value) {
                        $skip = true;
                        break;
                    }
                }
                if ($skip) {
                    continue;
                }
            }
            //
            $counter++;
            if ($counter > $limit[0] && $counter <= ($limit[0] + $limit[1])) {
                $data[$counter] = $row;
                $data[$counter]['_id'] = $id;
            }
            if ($counter > ($limit[0] + $limit[1])) {
                break;
            }
        }

        $result['total'] = count($rows);
        $result['data'] = $data;

        return $result;
    }
}