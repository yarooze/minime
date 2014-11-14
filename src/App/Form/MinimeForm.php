<?php

namespace App\Form;

/**
 *
 * @author jb
 *
 */
class MinimeForm
{
  protected $name = 'form_default';

  protected $data = array();
  protected $errs = array();

  protected $errors = array(
        'default' => 'Error!',
      );

  /**
   *
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * validates data and returns array with errors
   *
   * @param array $data
   * @return array - empty array if OK
   */
  public function validate()
  {
    return array();
  }

  public function bind($data) {
    $this->data = $data;
    $this->validate();
  }

  public function getErrors()
  {
    return $this->errs;
  }

  public function getErrorMsg($err_name) {
    $err = $this->errors['default'];
    if(isset($this->errors[$err_name])) {
      $err= $this->errors[$err_name];
    }
    return $err;
  }

  public function getValue($field) {
    return isset($this->data[$field]) ? $this->data[$field] : null;
  }
}