<?php


namespace app\db;


interface ConnectionInterface
{
    /**
     *
     * @param Application $app
     */
    public function __construct($app, $cfg = null);
}