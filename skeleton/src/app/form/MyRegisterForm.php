<?php

namespace app\form;

/**
 *
 * @author jb
 *
 */
class MyRegisterForm extends BaseForm
{
  protected $name = 'form_register';

  protected $errors = array(
      'default'=> 'Error!',
      'name'   => 'Name must be 3 - 25 chars long and consist only letters, numbers, minuses and dots!',
      'mail'   => 'Email is invalid!',
      'pwd'    => 'Password must be at least 6 chars long!',
      'pwd_eq' => 'Passwords are not equal!',
  );

  public function validate()
  {
    $data = $this->data;
    $errs = parent::validate();
    if(!isset($data['name']) || !preg_match("/^[a-zA-Z0-9\._-]{3,25}$/i", $data['name'])) {
      $errs['name'] = $this->getErrorMsg('name');
    }
    if(!isset($data['mail']) || !preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/", $data['mail'])) {
      $errs['mail'] = $this->getErrorMsg('mail');
    }
    if(!isset($data['pwd1']) || !isset($data['pwd2']) || strlen($data['pwd1']) < 6) {
      $errs['pwd'] = $this->getErrorMsg('pwd');
    } else if($data['pwd1'] != $data['pwd2']) {
      $errs['pwd_eq'] = $this->getErrorMsg('pwd_eq');
    }
    $this->errs = $errs;
  }
}