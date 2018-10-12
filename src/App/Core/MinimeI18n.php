<?php

namespace App\Core;


class MinimeI18n
{
    protected $app;
    protected $root_dir = '';
    protected $locale = 'en';

    protected $messages = array();

    /**
     * MinimeI18n constructor.
     * @param Application $app
     */
    public function __construct($app) {
        $this->app = $app;
        $this->root_dir = $app->config->get('APP_ROOT_DIR');
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public function trans($message, $params = array()) {
        $this->locale = $this->app->config->get('locale', $this->locale);
        if (!array_key_exists($this->locale, $this->messages)) {
            $this->messages[$this->locale] = array();
            $filename = $this->root_dir . '/i18n/'. $this->locale .'.php';
            if (file_exists($filename)) {
                $this->messages[$this->locale] = require $filename;
            }
        }

        $message = getAV($this->messages[$this->locale], $message, $message);
        foreach ($params as $key => $value) {
            $message = str_replace($key, $value, $message);
        }

        return $message;
    }
}