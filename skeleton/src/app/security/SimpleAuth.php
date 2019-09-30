<?php
namespace app\security;

use app\model\User;

/**
 *
 * @author jb
 */
Class SimpleAuth extends BaseAuth
{
    protected function make_salt() {
        return substr(md5(microtime(true)), 0, 8);
    }

    public function crypt_pass($password, $salt = null) {
        if (empty($salt)) {
            $salt = '$6$rounds=5000$' . $this->make_salt() . '$';
        }
        return crypt($password, $salt);
    }

    /**
     * Test if password matches the hash
     * (For the older users use iso fallback)
     *
     * @param $password
     * @param $user
     * @return bool
     */
    public function matchPassword($password, $user) {
        $cryptedPwd = $this->app->auth->crypt_pass($password, $user->getPassword());

        return $cryptedPwd === $user->getPassword();
    }

    /**
     * @param string $login
     * @param string $password
     * @param bool $keepForever
     * @return bool
     */
    public function loginUser($login, $password, $keepForever =  false) {
//        $db = $this->app->db;
//        $user = new User(array(),$db);
//        $userData = $user->retrieveOneBy('login', $login);
//        if (!$userData) {
//            return false;
//        }
//        $user->setFieldsFromArray($userData, false);
//
//        if (!$user->getActive()) {
//            return false;
//        }
//
//        if(!$this->matchPassword($password, $user)) {
//            return false;
//        }
//
//        $sessionUser = $this->app->session->get('user');
//        $sessionUser->setAuthenticated(self::IS_AUTHENTICATED_FULLY);
//        $sessionUser->setCredential('AUTHENTICATED_FULLY');
//
//        $sessionUser->setKeepForever($keepForever);
//
//        if ($user->getIsAdmin()) {
//            $sessionUser->setCredential('ADMIN');
//        }
//
//        $sessionUser->setUser($user);
//        $this->app->session->set('user', $sessionUser);

        return true;
    }

    public function isAuthenticated($atLeast = self::IS_AUTHENTICATED_ANONYMOUSLY)
    {
        return true;
//        $user = $this->app->session->get('user', null);
//        if(!$user || get_class($user) === '__PHP_Incomplete_Class')
//        {
//            $user = new SecurityUser();
//            $user->setId($this->app->session->getSessionId());
//            $user->setAuthenticated(self::IS_AUTHENTICATED_ANONYMOUSLY);
//        }
//        $this->app->session->set('user', $user);
//        return ($user->getAuthenticated() >= $atLeast);
    }
}