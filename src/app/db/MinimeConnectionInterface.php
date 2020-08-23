<?php


namespace app\db;


interface MinimeConnectionInterface
{
    /**
     *
     * @param Application $app
     */
    public function __construct($app, $cfg = null);


}