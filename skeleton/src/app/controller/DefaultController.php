<?php
namespace app\controller;

use app\view\DefaultView as DefaultView,
    app\view\HtmlView as HtmlView,
    app\form\RegisterForm as RegisterForm,
    app\form\LoginForm as LoginForm,
    app\core\Logger as Logger,
    app\security\SimpleAuth as Auth;

/**
 *
 * @author jb
 */
Class DefaultController extends BaseController
{
    public function defaultAction()
    {
        if (!$this->app->auth->isAuthenticated()) {
            $this->app->router->redirect('login');
        }

        //$view = new DefaultView($this->app);
        $view = new HtmlView($this->app);


        $data = $this->app->request->getParameters();

        //$form = new RegisterForm($this->app);
        // $form = new LoginForm($this->app);

        $params = array(
            'page_name' => 'default',
            'main_template_name' => 'Main',
            'template_name' => 'Default',
            'data' => $data,
            //'form' => $form
        );

        $view->render($params);
    }
}
