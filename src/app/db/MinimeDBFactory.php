<?php


namespace app\db;


use app\exception\MinimeException;

class MinimeDBFactory implements MinimeDBFactoryInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     *
     * @var array
     */
    protected $connections = array();

    /**
     *
     * @param Application $app
     */
    public function __construct($app) {
        $this->app = $app;
    }

    /**
     * @param $entityName
     * @return MapperInterface
     * @throws MinimeException
     */
    public function getMapper($entityName) {
        $this->app->loadHElper('ArrayHelper');
        $cfg = $this->app->cfg;
        $connectionType = getByPath('mapping.'.$entityName, $cfg, null);

        $connection = $this->getConnection($connectionType);

        $mapperName =  'App\Model\\'.$entityName.'Mapper'.$connectionType;
        $mapper = new $mapperName($this->app, $connection);

        return $mapper;
    }

    /**
     * @param $connectionType
     * @return ConnectionInterface
     * @throws MinimeException
     */
    public function getConnection($connectionType) {
        $this->app->loadHElper('ArrayHelper');
        $cfg = $this->app->cfg;
        if ($connectionType === null) {
            throw new MinimeException('Unknown mapping for model ' . $connectionType);
        }
        $connectionCfg = getByPath('db.'.$connectionType, $cfg, null);
        if ($connectionCfg === null) {
            throw new MinimeException('Missing configuration for connection ' . $connectionType);
        }
        $connectionName = 'App\DB\\'.$connectionType;
        if (!isset($this->connections[$connectionName])) {
            $this->connections[$connectionName] = new $connectionName($this->app, $connectionCfg);
        }
        $connection = $this->connections[$connectionName];

        return $connection;
    }
}