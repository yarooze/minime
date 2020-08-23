<?php

namespace app\form;


/**
 *
 * @author jb
 *
 */
class UserEditForm extends BaseForm
{
    protected $name = 'form_user_edit';

    protected $errors = array(
        'default' => 'Error!',
        'login' => 'Name must be 3 - 25 chars long and consist only letters, numbers, minuses and dots!',
        'email' => 'Email is invalid!',
        'pwd' => 'Password must be at least 6 chars long!',
        'pwd_eq' => 'Passwords are not equal!',
    );

    public function validate()
    {
        $this->fixCheckboxes(array('active',));

        if (isset($this->data['credentials']) && is_array($this->data['credentials'])) {
            $this->data['credentials'] = implode(',',array_keys($this->data['credentials']));
        }

        $data = $this->data;
        $errs = parent::validate();
        if (!isset($data['login']) || !preg_match("/^[a-zA-Z0-9\._-]{3,25}$/i", $data['login'])) {
            $errs['login'] = $this->getErrorMsg('login');
        }
        if (!isset($data['email']) || !preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/", $data['email'])) {
            $errs['email'] = $this->getErrorMsg('email');
        }

        if (isset($data['pwd1']) || isset($data['pwd2'])) {
            if ($data['pwd1'] !== '' && strlen($data['pwd1']) < 5) {
                $errs['pwd'] = $this->getErrorMsg('pwd');
            }
            if ($data['pwd1'] !== $data['pwd2']) {
                $errs['pwd_eq'] = $this->getErrorMsg('pwd_eq');
            }
            if (empty($errs) && !empty($data['pwd1']) && !empty($data['pwd2']) ) {
                $cryptedPwd = $this->app->auth->crypt_pass($data['pwd1']);
                $this->data['password'] = $cryptedPwd;
            }
            if (empty($this->data['password'])) {
                $errs['pwd'] = $this->getErrorMsg('pwd');
            }
        }
        $this->errs = $errs;
    }
}
