<?php

namespace App\Core;


class MinimeI18n
{
    protected $app;
    protected $root_dir = '';
    protected $locale = 'en';

    protected $messages = array();

    public function __construct($app) {
        $this->app = $app;
        $this->root_dir = $app->config->get('APP_ROOT_DIR');
    }

    public function trans($message) {
        $this->locale = $this->app->config->get('locale', $this->locale);
        if (!array_key_exists($this->locale, $this->messages)) {
            $this->messages[$this->locale] = array();
            $filename = $this->root_dir . '/i18n/'. $this->locale .'.php';
            if (file_exists($filename)) {
                $this->messages[$this->locale] = require $filename;
            }
        }
        return getAV($this->messages[$this->locale], $message, $message);
    }
}