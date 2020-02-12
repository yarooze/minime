<?php

namespace app\model;


class User extends DBModel
{

    protected $id_field  = 'id';

    protected $id;
    protected $is_admin;
    protected $active;
    protected $login;
    protected $email;
    protected $password;
    protected $created;
    protected $updated;

    // protected $credentials = null;

    /**
     * @inheritDoc
     */
    protected $mapping = array(
        'id' => array(
            'getset' => 'Id',
            'field' => 'id',
            'dbfield' => 'id',
        ),
        'isadmin' => array(
            'getset' => 'IsAdmin',
            'field' => 'is_admin',
            'dbfield' => 'is_admin',
        ),
        'active' => array(
            'getset' => 'Active',
            'field' => 'active',
            'dbfield' => 'active',
        ),
        'login' => array(
            'getset' => 'Login',
            'field' => 'login',
            'dbfield' => 'login',
        ),
        'email' => array(
            'getset' => 'Email',
            'field' => 'email',
            'dbfield' => 'email',
        ),
        'password' => array(
            'getset' => 'Password',
            'field' => 'password',
            'dbfield' => 'password',
        ),
        'created' => array(
            'getset' => 'Created',
            'field' => 'created',
            'dbfield' => 'created',
        ),
        'updated' => array(
            'getset' => 'Updated',
            'field' => 'updated',
            'dbfield' => 'updated',
        ),
//        'credentials' => array(
//            'getset' => 'Credentials',
//            'field' => 'credentials',
//            'dbfield' => 'credentials',
//        ),
    );

    public function __construct(array $params = array(), $con = null)
    {
        $this->tablename = 'user';
        $this->alias     = 'u';

        parent::__construct($params, $con);
    }

    protected function joinFields() {
        return ''; // ', group_concat(crs.credentials) AS credentials ';
    }

    protected function joinQuery() {
        return ''; // ' LEFT JOIN credentials AS crs ON u.id = crs.user_id ';
    }

    /**
     * We must add some parameter her to let the join work
     *
     * @inheritDoc
     */
    public function retriveCollection(&$params = array())
    {
        $group = isset($params['groupby']) ? $params['groupby'] : array();
        if (!in_array($this->id_field, $group)) {
            //$group[] = '' . $this->alias . '.' . $this->id_field . '';
            $group[] = '' . $this->id_field . '';
        }
        $params['groupby'] = $group;

        return parent::retriveCollection($params);
    }

    /**
     * @inheritDoc
     */
    protected function init($params = array())
    {
        // TODO: Implement init() method.
    }

    /**
     * @inheritDoc
     */
    protected function canBeSaved()
    {
        $validate = $this->validate();
        return empty($validate);
    }

    /**
     * Test unique fields
     * @return array - 'err' => null | array('err1', 'err2')
     */
    public function validate() {
        $data = null;
        $q = 'SELECT
          ' . $this->alias . '.* 
          FROM `' . $this->tablename . '` AS ' . $this->alias . '          
          WHERE ( `login` = :login OR `email` = :email ) AND `id` <> :id 
          ;';
        $stmt = $this->getDb()->prepareStatement($q);
        if ($stmt->execute(array(':login' => $this->login, ':email' => $this->email, ':id' => $this->id))) {
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        if (!$data) {
            return null;
        }

        $errs = array();
        foreach ($data as $row) {
            if ($row['login'] === $this->login) {
                $errs['login'] = 'LOGIN_EXISTS';
            }
            if ($row['email'] === $this->email) {
                $errs['email'] = 'EMAIL_EXISTS';
            }
        }
        return $errs;
    }

    public function save($testCompulsoryFields = true)
    {
        // Save main entity
        if (!parent::save($testCompulsoryFields)) {
            return false;
        }

        try {
            // delete old connections
//            $q_delete = 'DELETE FROM `credentials` WHERE user_id = :user_id ';
//            $stmt = $this->getDb()->prepareStatement($q_delete);
//            $stmt->execute(array(':user_id' => $this->getId()));
//
//            // add new connections if any
//            $credentials = $this->getCredentials(true);
//            if (empty($credentials)) {
//                return true;
//            }
//
//            $q_insert_params = array();
//            $values_part = '';
//            foreach ($credentials as $key => $credential) {
//                $values_part .= ($values_part === '') ? '' : ', ';
//                $values_part .= '  ( :user_id_' . $key . ', :credential_' . $key . ' )';
//                $q_insert_params[':credential_' . $key . ''] = $credential;
//                $q_insert_params[':user_id_' . $key . ''] = $this->getId();
//            }
//            $q_insert = 'INSERT INTO `credentials` ' .
//                ' ( user_id, credentials ) VALUES ' .
//                $values_part .' ;';
//            $stmt = $this->getDb()->prepareStatement($q_insert);
//            $stmt->execute($q_insert_params);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function insert()
    {
        $q = 'INSERT INTO `' . $this->tablename . '`'.
            ' ( active, is_admin, login, email, password ) VALUES ' .
            '( :active, :is_admin, :login, :email, :password );';

        $stmt = $this->getDb()->prepareStatement($q);
        $stmt->execute(array(
            ':active' => $this->getActive(),
            ':is_admin' => $this->getIsAdmin(),
            ':login' => $this->getLogin(),
            ':email' => $this->getEmail(),
            ':password' => $this->getPassword(),
        ));

        $this->setId($this->getLastInsertId());
    }

    /**
     * @inheritDoc
     */
    protected function update()
    {
        $q = 'UPDATE `'.$this->tablename.'` AS '.$this->alias.' SET ' .
            ' active=:active, is_admin=:is_admin, login=:login, email=:email, password=:password ' .
            ' WHERE '.$this->alias.'.'.$this->id_field.' = :id;';

        $stmt = $this->getDb()->prepareStatement($q);

        $stmt->execute(array(
            ':id' => $this->getId(),
            ':active' => $this->getActive(),
            ':is_admin' => $this->getIsAdmin(),
            ':login' => $this->getLogin(),
            ':email' => $this->getEmail(),
            ':password' => $this->getPassword()
        ));
    }

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
        return $this->active;
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
    public function getIsAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @param mixed $is_admin
     */
    public function setIsAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
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
        return $credentials;
    }

    /**
     * @param null $credentials
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

}