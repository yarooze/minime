<?php


namespace App\DB;


class JSON implements ConnectionInterface
{
    /**
     * @var Application
     */
    public $app;

    /**
     *
     * @param Application $app
     */
    public function __construct($app, $cfg = null)
    {

        $this->app = $app;
        //$cfg = $app->config->get('db');
    }
}