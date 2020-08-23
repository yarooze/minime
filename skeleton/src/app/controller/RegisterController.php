<?php

namespace app\controller;

use app\core\Flasher;
use app\core\I18n;
use app\model\UserEntity;
use app\view\HtmlView as HtmlView,
    app\form\RegisterForm as RegisterForm;

/**
 *
 * @author jb
 */
class RegisterController extends BaseController
{
    public function defaultAction()
    {
        $view = new HtmlView($this->app);

        $data = $this->app->request->getParameters();

        $form = new RegisterForm($this->app);

        /** @var I18n $i18n */
        $i18n = $this->app->i18n;
        $data['i18n'] = $i18n->trans('MSG');

        $params = array(
            'main_template_name' => 'Main',
            'template_name' => 'Register',
            'data' => $data,
            'form' => $form,
            'errs' => []
        );

        $view->render($params);
    }

    public function submitAction()
    {
        $view = new HtmlView($this->app);
        $form = new RegisterForm($this->app);
        $data = $this->app->request->getParameter($form->getName(), array());
        $form->bind($data);
        $errs = $form->getErrors();

        //no errors - proceed
        if (empty($errs)) {

            /** @var DBFactoryInterface $dbFactory */
            $dbFactory = $this->app->dbFactory;
            /** @var MapperInterface $userMapper */
            $userMapper = $dbFactory->getMapper('User');

            $user = new UserEntity($userMapper);
            $user->setLogin($data['login']);
            $user->setEmail($data['mail']);
            $user->setActive(0);
            $cryptedPwd = $this->app->auth->crypt_pass($data['pwd1']);
            $user->setPassword($cryptedPwd);

            if ($userMapper->save($user)) {
                $flasher = new Flasher($this->app);
                $i18n = $this->app->i18n;
                $flasher->add($i18n->trans('WELCOME'), Flasher::LVL_NOTICE);

                $this->app->router->redirect('registerSuccess', array());
            }
            $errs['registerError'] = 'Your registration was not possible!';
        }

        $view->render(array('form' => $form, 'errs' => $errs));
    }

    public function registerSuccessAction()
    {
        $view = new HtmlView($this->app);

        $params = array(
            'page_name' => 'default',
            'main_template_name' => 'Main',
            'template_name' => 'Default',
            //'data' => $data,
            //'form' => $form
        );

        $view->render($params);
    }
}
