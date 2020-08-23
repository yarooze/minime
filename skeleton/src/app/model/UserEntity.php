<?php

namespace app\model;


class UserEntity extends BaseEntity
{
    protected $id;
    protected $active;
    protected $login;
    protected $email;
    protected $password;
    protected $created;
    protected $updated;

    protected $credentials = '';

    /**
     * @inheritDoc
     */
    protected $mapping = array(
        'id' => array(
            'getset' => 'Id',
            'field' => 'id',
        ),
        'active' => array(
            'getset' => 'Active',
            'field' => 'active',
        ),
        'login' => array(
            'getset' => 'Login',
            'field' => 'login',
        ),
        'email' => array(
            'getset' => 'Email',
            'field' => 'email',
        ),
        'password' => array(
            'getset' => 'Password',
            'field' => 'password',
        ),
        'created' => array(
            'getset' => 'Created',
            'field' => 'created',
        ),
        'updated' => array(
            'getset' => 'Updated',
            'field' => 'updated',
        ),
        'credentials' => array(
            'getset' => 'Credentials',
            'field' => 'credentials',
        ),
    );

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return (int)$this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return null
     */
    public function getCredentials($asArray = false)
    {
        $credentials = ($asArray) ? explode(',', $this->credentials) : $this->credentials;
        if ($asArray && count($credentials) === 1 && $credentials[0] === '') {
            $credentials = array();
        }
        $this->fixCredentials();
        return $credentials;
    }

    /**
     * @param null $credentials
     */
    public function setCredentials($credentials)
    {
        if (is_array($credentials) ) {
            $credentials = implode(',', $credentials);
        }
        $credentials = (string)$credentials;
        $this->credentials = $credentials;
        $this->fixCredentials();
    }

    protected function fixCredentials() {
        $credentials = $this->credentials;
        $credentials = explode(',', $credentials);
        $credentials = array_map('trim', $credentials);
        $credentials = array_filter($credentials);
        $credentials = array_unique($credentials);
        $credentials = implode(',', $credentials);
        $this->credentials = $credentials;
    }
}