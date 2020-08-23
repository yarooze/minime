<?php

namespace app\model;


class UserMapperPDO extends BaseMapperPDO
{

    protected $entityname = 'User';

    protected $id_field  = 'id';

    protected $app  = null;

    /**
     * @inheritDoc
     */
    protected $mapping = array(
        'id' => array(
            'dbfield' => 'id',
        ),
        'active' => array(
            'dbfield' => 'active',
        ),
        'login' => array(
            'dbfield' => 'login',
        ),
        'email' => array(
            'dbfield' => 'email',
        ),
        'password' => array(
            'dbfield' => 'password',
        ),
        'created' => array(
            'dbfield' => 'created',
        ),
        'updated' => array(
            'dbfield' => 'updated',
        ),
        'credentials' => array(
            'dbfield' => 'credentials',
        ),
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
        if ($this->getConnection()->executeStatement($q, array(
            ':login' => $entity->getLogin(),
            ':email' => $entity->getEmail(),
            ':id' => $entity->getId()
        ))) {
            $data = $this->getConnection()->fetch();
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

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function insert($entity)
    {
        $q = 'INSERT INTO `' . $this->tablename . '`'.
            ' ( active, login, email, password, credentials ) VALUES ' .
            '( :active, :login, :email, :password, :credentials );';


        $this->getConnection()->executeStatement($q, array(
            ':active' => $entity->getActive(),
            ':login' => $entity->getLogin(),
            ':email' => $entity->getEmail(),
            ':password' => $entity->getPassword(),
            ':credentials' => $entity->getCredentials(),
        ));

        $entity->setId($this->getConnection()->getLastInsertId());
    }

    /**
     * @inheritDoc
     */
    protected function update($entity)
    {
        $q = 'UPDATE `'.$this->tablename.'` AS '.$this->alias.' SET ' .
            ' active=:active, login=:login, email=:email, password=:password, credentials=:credentials ' .
            ' WHERE '.$this->alias.'.'.$this->id_field.' = :id;';

        $this->getConnection()->executeStatement($q, array(
            ':id' => $entity->getId(),
            ':active' => $entity->getActive(),
            ':login' => $entity->getLogin(),
            ':email' => $entity->getEmail(),
            ':password' => $entity->getPassword(),
            ':credentials' => $entity->getCredentials(),
        ));
    }

}