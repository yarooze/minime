<?php

namespace app\controller;


/**
 *
 * @author jb
 */
Class UserCrudController extends BaseCrudController
{
    /**
     * Full model class name (e.g. 'App\Model\User')
     *
     * @var string|null
     */
    protected $modelName = 'User';

    protected $viewNameList = 'app\view\CrudView';
    protected $viewNameEdit = 'app\view\CrudView';

    protected $templateList = 'CrudList';
    protected $templateEdit = 'CrudEdit';

    protected $pageNameList = 'User list';
    protected $pageNameEdit = 'Edit user';

    protected $formNameEdit = 'app\form\UserEditForm';
    protected $formNameDelete = 'app\form\BaseForm';

    protected $routeEdit = 'userCrudEdit';
    protected $routeView = 'userCrudView';
    protected $routeList = 'userCrudList';
    protected $routeDelete = 'userCrudDelete';

    protected $pagerLimit = 25;

    protected $fieldsEdit = array(
        'id' => array('attr' => array('readonly' => 'readonly')),
        'active' => array('type' => 'checkbox'),
        'login' => array(),
        'email' => array(),
        'token' => array(),
        'password' => array('type' => 'hidden'),
        'pwd1' => array('attr' => array('type' => 'password')),
        'pwd2' => array('attr' => array('type' => 'password')),
        'credentials' => array(),
        'created' => array('attr' => array('readonly' => 'readonly')),
        'updated' => array('attr' => array('readonly' => 'readonly')),
    );
    protected $fieldsView = array(
        'id' => array(), 'active' => array(), 'login' => array(), 'email' => array(), 'token' => array(),
        'credentials' => array('partial' => 'crudViewCSVTagsField'), 'password' => array(), 'created' => array(), 'updated' => array(),
    );
    protected $fieldsList = array(
        'id' => array('orderby' => true), 'active' => array('orderby' => true, 'partial' => 'crudListCheckboxField'),
        'login' => array('orderby' => true), 'credentials' => array('partial' => 'crudListCSVField'));

    protected $filter_cfg = array(
        'id' => array('title' => 'ID'),
        'login' => array('title' => 'login'),
    );
}
