<?php


namespace App\DB;


interface ConnectionInterface
{
    /**
     *
     * @param Application $app
     */
    public function __construct($app, $cfg = null);
}