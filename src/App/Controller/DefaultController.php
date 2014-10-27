<?php
namespace App\Controller;

require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../View/DefaultView.php';

use App\View\DefaultView as DefaultView;

/**
 *
 * @author jb
 */
Class DefaultController extends BaseController
{
  public function defaultAction()
  {
    $view = new DefaultView($this->app);

    $data = $this->app->request->getParameters();

    $form = new MyRegisterForm();

    $view->render(array('data' => $data,'form' => $form));
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

  public function myRegisterSuccessAction() {
//     $view = new DefaultView($this->app);
//     $data = $this->app->request->getParameters();

//     $view->render(array('registerSuccess' => true,'preview'=>$preview));
  }
}
