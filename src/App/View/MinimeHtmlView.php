<?php

namespace App\View;

// require_once __DIR__.'/BaseView.php';
// require_once __DIR__.'/../Helper/HtmlHelper.php';

/**
 *
 * @author jb
 */
Class MinimeHtmlView extends BaseView
{
    public function render($params)
    {
        $this->main_template_name = '/../Templates/Main.tpl.php';
        if (!isset($params['page_name'])) {
            $params['page_name'] = $this->page_name;
        }
        parent::render($params);
    }
}