<?php

namespace App\Controller;

use App\Core\I18n;
use App\DB\DBFactoryInterface;
use App\DB\PDO;
use App\Model\MapperInterface;
use App\View\DefaultView as DefaultView,
    App\View\HtmlView as HtmlView,
    App\Form\MyRegisterForm as RegisterForm;

/**
 *
 * @author jb
 */
Class ExampleController extends BaseController
{
    public function defaultAction()
    {
        //$view = new DefaultView($this->app);
        $view = new HtmlView($this->app);

        $data = $this->app->request->getParameters();

        $form = new RegisterForm();

        /** @var DBFactoryInterface $dbFactory */
        $dbFactory = $this->app->dbFactory;


        // direct queries
        /** @var PDO $db */
        // $db = $dbFactory->getConnection('PDO');
        // var_dump($db->query('SELECT * FROM `user_credential`'));
        //
        //$db = $dbFactory->getConnection('PDO');
        //var_dump($db->fetchWithStatement(
        //  'SELECT * FROM `user_credential` WHERE user_id = :user_id;',
        //  array(':user_id' => 2)
        //));


        // Mappers
        /** @var MapperInterface $userMapper */
        $userMapper = $dbFactory->getMapper('User');
        /** @var  $user */
        // $user = $userMapper->retrieveById(1);
        // $user = $userMapper->retrieveOneBy('id', 1);

        $args = array('filter' => array(
            'OR' => array(
                'OR' => array(
                    array('active' => array(0,1)),
                    array('active' => '>=1'),
                    array('password' => '<>0'),
                    array('password' => 'LIKE%D3%'),
                ),
                'login' => 'user'
            ),
        ));
        $users = $userMapper->retrieveCollection($args, false);
        //var_dump($users);

        /** @var I18n $i18n */
        $i18n = $this->app->i18n;
        $data['i18n'] = $i18n->trans('MSG');

        $data['users'] = $users;

        $params = array(
            'main_template_name' => 'Main',
            'template_name' => 'Body',
            'data' => $data,
            'form' => $form
        );

        $view->render($params);
    }

    public function mySubmitAction()
    {
//     $view = new DefaultView($this->app);
//     $form = new MyRegisterForm();
//     $data = $this->app->request->getParameter($form->getName(), array());
//     $form->bind($data);
//     $errs = $form->getErrors();

//     //no errors - proceed
//     if(empty($errs)) {
//       $user = new User();
//       $user->setName($data['name']);
//       $user->setMail($data['mail']);
//       $user->setPassword($data['pwd1']);
//       if ($user->save()) {
//         $this->app->router->redirect('MyRegisterSuccess', array());
//       }
//       $errs['registerError'] = 'Your registration was not possible!';
//     }

//     $view->render(array('form' => $form, 'errs'=>$errs));
    }

    public function myRegisterSuccessAction()
    {
//     $view = new DefaultView($this->app);
//     $data = $this->app->request->getParameters();

//     $view->render(array('registerSuccess' => true,'preview'=>$preview));
    }
}
