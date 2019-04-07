<?php
namespace App\View;

/**
 *
 * @author jb
 */
Abstract Class BaseView extends MinimeBaseView
{
    /**
     * Call it with parent:: to get parent templates
     */
    public function getTemplateDir () {
        return __DIR__.'/../Templates/';
    }

    /**
     * renders the partial
     *
     * @param string $partial - partial's name (without "_")
     * @param array  $params  - variables for the partial
     */
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