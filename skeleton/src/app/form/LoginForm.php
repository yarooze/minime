<?php

namespace app\form;

/**
 *
 * @author jb
 *
 */
class LoginForm extends BaseForm
{
  protected $name = 'form_login';

  protected $errors = array(
      'default'=> 'Error!',
      'email'   => 'Email is invalid!',
      'pwd'    => 'Password must be at least 6 chars long!',
  );

  public function validate()
  {
    $data = $this->data;
    $errs = array();
    if(!isset($data['email']) || !preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/", $data['email'])) {
      $errs['email'] = $this->getErrorMsg('email');
    }
    if(!isset($data['pwd']) || !isset($data['pwd']) || strlen($data['pwd']) < 6) {
      $errs['pwd'] = $this->getErrorMsg('pwd');
    }
    $this->errs = $errs;
  }
}