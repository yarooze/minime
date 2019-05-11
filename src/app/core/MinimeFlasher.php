<?php

namespace App\Core;


class MinimeFlasher
{
    const LVL_NOTICE = 'notice';
    const LVL_ALERT = 'alert';
    const LVL_ERROR = 'err';

    protected $app;

    protected $flashesId = 'flashes';

    public function __construct($app) {
        $this->app = $app;
    }

    public function add($msg, $lvl = self::LVL_NOTICE) {
        $flashes = $this->app->session->get($this->flashesId, array());
        $flashes[] = array(
            'lvl' => $lvl,
            'msg' => $msg
        );
        $this->app->session->set($this->flashesId, $flashes);
    }

    public function getAll() {
        $flashes = $this->app->session->get($this->flashesId, array());
        $this->app->session->set($this->flashesId, array());
        return $flashes;
    }
}