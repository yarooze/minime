<?php

namespace App\Model;


class UserMapperPDO extends BaseMapperPDO
{

    protected $id_field  = 'id';

    protected $app  = null;

    /**
     * @inheritDoc
     */
    protected $mapping = array(
        'id' => array(
            'getset' => 'Id',
            'field' => 'id',
            'dbfield' => 'id',
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

    public function __construct($app, $con = null)
    {
        $this->tablename = 'user';
        $this->alias     = 'u';

        parent::__construct($app, $con);
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
    public function retrieveCollection(&$params = array(), $asEntites = false)
    {
        $group = isset($params['groupby']) ? $params['groupby'] : array();
        if (!in_array($this->id_field, $group)) {
            //$group[] = '' . $this->alias . '.' . $this->id_field . '';
            $group[] = '' . $this->id_field . '';
        }
        $params['groupby'] = $group;

        return parent::retrieveCollection($params);
    }

    /**
     * Test unique fields
     * @return null | array('err1', 'err2')
     */
    public function validate($entity) {
        $data = null;
        $q = 'SELECT
          ' . $this->alias . '.* 
          FROM `' . $this->tablename . '` AS ' . $this->alias . '          
          WHERE ( `login` = :login OR `email` = :email ) AND `id` <> :id 
          ;';
        $stmt = $this->getConnection()->prepareStatement($q);
        if ($stmt->execute(array(
            ':login' => $entity->getLogin(),
            ':email' => $entity->getEmail(),
            ':id' => $entity->getId()
        ))) {
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        $errs = array();
        if (!$data) {
            return $errs;
        }

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
//            $stmt = $this->getConnection()->prepareStatement($q_delete);
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
//            $stmt = $this->getConnection()->prepareStatement($q_insert);
//            $stmt->execute($q_insert_params);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function insert($entity)
    {
        $q = 'INSERT INTO `' . $this->tablename . '`'.
            ' ( active, is_admin, login, email, password ) VALUES ' .
            '( :active, :is_admin, :login, :email, :password );';

        $stmt = $this->getConnection()->prepareStatement($q);
        $stmt->execute(array(
            ':active' => $entity->getActive(),
            ':login' => $entity->getLogin(),
            ':email' => $entity->getEmail(),
            ':password' => $entity->getPassword(),
        ));

        $entity->setId($this->getLastInsertId());
    }

    /**
     * @inheritDoc
     */
    protected function update($entity)
    {
        $q = 'UPDATE `'.$this->tablename.'` AS '.$this->alias.' SET ' .
            ' active=:active, login=:login, email=:email, password=:password ' .
            ' WHERE '.$this->alias.'.'.$this->id_field.' = :id;';

        $stmt = $this->getConnection()->prepareStatement($q);

        $stmt->execute(array(
            ':id' => $entity->getId(),
            ':active' => $entity->getActive(),
            ':login' => $entity->getLogin(),
            ':email' => $entity->getEmail(),
            ':password' => $entity->getPassword()
        ));
    }

}