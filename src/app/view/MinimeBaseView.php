<?php
namespace app\view;

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
        return __DIR__.'/../templates/';
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
    //abstract public function renderPartial($partial, $params);
    public function renderPartial($partial, $params) {
        $app = $this->app;
        $view = $this;
        extract($params);

        $templateDir = $this->getTemplateDir();
        if (!file_exists ($templateDir . '_'.$partial.'.tpl.php')) {
            $templateDir = parent::getTemplateDir();
        }
        include $templateDir . '_'.$partial.'.tpl.php';
    }

    /**
     * renders the template
     *
     * @param string $template - template name  (without `.tpl.php`)
     * @param array  $params  - variables for the partial
     */
    public function renderTemplate($template, $params) {
        $app = $this->app;
        $view = $this;
        extract($params);

        $templateDir = $this->getTemplateDir();
        if (!file_exists ($templateDir . $template.'.tpl.php')) {
            $templateDir = parent::getTemplateDir();
        }
        include $templateDir . $template.'.tpl.php';
    }
    
    /**
     * renders the template
     *
     * @param array  $params  - variables for the template
     */
    //abstract public function render($params);
    public function render($params)
    {
        $this->sendHeaders();
        if ($this->main_template_name === null) {           
            $this->renderTemplate($this->template_name, $params);
        } else { // Render main template first. View template will be inserted in it.
            $this->renderTemplate($this->main_template_name, $params);
        }
    }
}