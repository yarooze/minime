<?php


namespace app\db;


interface MinimeDBFactoryInterface
{
    public function __construct($app);

    /**
     * @param $entityName
     * @return MapperInterface
     * @throws MinimeException
     */
    public function getMapper($entityName);

    /**
     * @param $connectionType
     * @return ConnectionInterface
     * @throws MinimeException
     */
    public function getConnection($connectionType);
}