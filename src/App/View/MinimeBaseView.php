<?php
namespace App\View;

/**
 *
 * @author jb
 */
Abstract Class MinimeBaseView
{
    /**
     * @var \App\Application
     */
    protected $app = null;

    /**
     *
     * @var string
     */
    protected $template_name = '';

    /**
     *
     * @var string|null
     */
    protected $main_template_name = null;

    /**
     * @var string|null
     */
    protected $page_name  = null;

    protected $headers = array();

    public function __construct(\App\Application $app)
    {
        $this->app = $app;
        $this->prepareHeaders();
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->template_name;
    }

    /**
     *
     */
    protected function prepareHeaders()
    {
        $this->headers[] = 'content-type: text/html; charset='.$this->app->config->get('charset');
    }

    /**
     *
     */
    public function sendHeaders()
    {
        foreach ($this->headers as $header)
        {
            header($header);
        }
    }

    /**
     * Call it with parent:: to get parent templates
     */
    public function getTemplateDir () {
        return __DIR__.'/../Templates/';
    }

    /**
     * @param string $string
     * @param boool  $raw
     */
    public function printString($string, $raw = false) {
        if($raw) {
            echo $string;
        } else {
            echo $this->app->escape($string);
        }
    }

    /**
     * renders the partial
     *
     * @param string $partial - partial's name (without "_")
     * @param array  $params  - variables for the partial
     */
    abstract public function renderPartial($partial, $params);

    /**
     * renders the template
     *
     * @param array  $params  - variables for the template
     */
    abstract public function render($params);
}