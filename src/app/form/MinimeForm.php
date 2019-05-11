<?php

namespace app\form;

use app\ApplicationInterfece;

/**
 *
 * @author jb
 *
 */
class MinimeForm
{
  protected $app;
  
  protected $csrf_tocken;

  protected $name = 'form_default';

  protected $data = array();
  protected $errs = array();

  protected $errors = array(
        'default' => 'Error!',
        'csrf_tocken' => 'csrf_forgery'
      );

  protected $fields = array (
      /* put your custom stuff here e.g.
       'id' => array(
        'type' => 'number'
      )
      */
  );

  public function __construct(ApplicationInterfece $app, $param = array())
  {
    $this->app = $app;
      if (isset($param['form_name'])) {
          $this->name  = $param['form_name'];
      }
      if (isset($param['form_errors'])) {
          $this->errors  = $param['form_errors'];
      }
      if (isset($param['form_fields'])) {
          $this->fields  = $param['form_fields'];
      }
  }

    /**
   *
   * @return string
   */
  public function getName()
  {
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
    $err = array();
    $data = $this->data;
    if ($this->csrf_tocken && (!isset($data['csrf_tocken']) || $data['csrf_tocken'] !== $this->csrf_tocken )) {
      $errs['csrf_tocken'] = $this->getErrorMsg('csrf_forgery');
    }
    return $err;
  }
  
    protected function fixCheckboxes($checkboxes)
    {
        foreach ($checkboxes as $checkbox) {
            if (!isset($this->data[$checkbox])) {
                $this->data[$checkbox] = 0;
                continue;
            }
            $this->data[$checkbox] = ($this->data[$checkbox] === 'on')? 1: $this->data[$checkbox];
        }
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

    /**
     *
     * @return string
     */
    public function getFullFieldName($fieldName) {
        return $this->name . '[' . $fieldName . ']';
    }

    public function generateCsrfTocken($globalTocken = '') {
        $this->csrf_tocken = md5($this->getName() . $globalTocken);
    }

    public function getCsrfTocken() {
        return $this->csrf_tocken;
    }




}