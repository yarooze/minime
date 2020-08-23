<?php
namespace app\controller;

use app\core\Flasher;
use app\model\MinimeEntityInterface;
use app\model\UserEntity;
use app\security\SecurityUser;
use app\view\DefaultView as DefaultView,
    app\view\HtmlView as HtmlView,
    app\form\RegisterForm as RegisterForm,
    app\form\LoginForm as LoginForm,
    app\core\Logger as Logger,
    app\security\SimpleAuth as Auth;
use app\view\LoginView;

/**
 *
 * @author jb
 */
Class SecurityController extends BaseController
{
    public function loginAction()
    {
        //$view = new DefaultView($this->app);
        $view = new LoginView($this->app);


        $data = $this->app->request->getParameters();

        //$form = new RegisterForm($this->app);
        $form = new LoginForm($this->app);

        $params = array(
            'main_template_name' => 'Main',
            'template_name' => 'Login',
            'data' => $data,
            'form' => $form
        );

        $view->render($params);
    }

    public function loginSubmitAction()
    {
        $this->app->loadHelper('ArrayHelper');
        $flasher = new Flasher($this->app);
        $i18n = $this->app->i18n;
        $view = new DefaultView($this->app);
        $form = new LoginForm($this->app);
        $data = $this->app->request->getParameter($form->getName(), array());
        $form->bind($data);
        $errs = $form->getErrors();
        //no errors - proceed
        if(empty($errs)) {
            if($this->app->auth->loginUser($form->getValue('email'), $form->getValue('pwd'))) {
                $flasher->add($i18n->trans('WELCOME'), Flasher::LVL_NOTICE);
                $this->app->router->redirect('default');
            }
        }

        $flasher->add($i18n->trans('CANNOT_LOGIN'), Flasher::LVL_ERROR);
        //$view->render(array('form' => $form, 'errs'=>$errs));
        $this->app->router->redirect('logout', array());
    }

    public function logoutAction()
    {
        $this->app->session->set('user', null);
        $this->app->router->redirect('login', array());
    }
}
