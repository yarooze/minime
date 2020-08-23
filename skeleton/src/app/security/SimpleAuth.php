<?php
namespace app\security;

use app\core\Flasher;
use app\model\MinimeEntityInterface;
use app\model\User;
use app\security\SimpleAuth as Auth;

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

        //var_dump($login, $password);die();
        /** @var DBFactoryInterface $dbFactory */
        $dbFactory = $this->app->dbFactory;
        /** @var MapperInterface $userMapper */
        $userMapper = $dbFactory->getMapper('User');

        /** @var MinimeEntityInterface */
        $dbUser = $userMapper->retrieveOneBy('email', $login, true);

        if($this->app->auth->matchPassword($password, $dbUser) &&
            $dbUser->getActive()) {
            /** @var SecurityUser $user */
            $sessionUser = $this->app->session->get('user');
            $sessionUser->setAuthenticated(Auth::IS_AUTHENTICATED_FULLY);
            $sessionUser->setCredential('AUTHENTICATED_FULLY');
            $sessionUser->setUser($dbUser);

            $credentials = $dbUser->getCredentials(true);
            foreach ($credentials as $credential) {
                $sessionUser->setCredential($credential);
            }

            $this->app->session->set('user', $sessionUser);
            return true;
        }

        return false;
    }

    public function isAuthenticated($atLeast = self::IS_AUTHENTICATED_ANONYMOUSLY)
    {
        $user = $this->app->session->get('user', null);
        if(!$user || get_class($user) === '__PHP_Incomplete_Class')
        {
            $user = new SecurityUser();
            $user->setId($this->app->session->getSessionId());
            $user->setAuthenticated(self::IS_AUTHENTICATED_ANONYMOUSLY);
        }
        $this->app->session->set('user', $user);
        return ($user->getAuthenticated() >= $atLeast);
    }
}