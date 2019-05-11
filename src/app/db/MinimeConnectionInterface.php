<?php


namespace App\DB;


interface MinimeConnectionInterface
{
    /**
     *
     * @param Application $app
     */
    public function __construct($app, $cfg = null);
}