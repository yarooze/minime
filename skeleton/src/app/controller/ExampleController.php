<?php
namespace App\Controller;

use App\Core\I18n;
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

    /** @var I18n $i18n */
    $i18n = $this->app->i18n;
    $data['i18n'] = $i18n->trans('MSG');

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

  public function myRegisterSuccessAction() {
//     $view = new DefaultView($this->app);
//     $data = $this->app->request->getParameters();

//     $view->render(array('registerSuccess' => true,'preview'=>$preview));
  }
}
