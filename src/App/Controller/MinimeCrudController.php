<?php

namespace App\Controller;

use App\Core\Flasher;

/**
 *
 * @author jb
 */
Abstract Class MinimeCrudController extends BaseController
{
    protected $actions = array('LIST', 'VIEW', 'CREATE', 'EDIT', 'DELETE');

    /**
     * Full model class name (e.g. 'App\Model\User')
     *
     * @var string|null
     */
    protected $modelName = null;

    protected $viewNameList = 'App\View\MinimeCrudView';
    protected $viewNameEdit = 'App\View\MinimeCrudView';
    protected $viewNameView = 'App\View\MinimeCrudView';

    protected $templateList = '/../Templates/CrudList.tpl.php';
    protected $templateEdit = '/../Templates/CrudEdit.tpl.php';
    protected $templateView = '/../Templates/CrudView.tpl.php';

    protected $pageNameList = 'List';
    protected $pageNameEdit = 'Edit';
    protected $pageNameView = 'View';

    protected $formNameEdit = 'App\Form\BaseForm';
    protected $formNameDelete = 'App\Form\BaseForm';

    protected $routeList = '{crud}List';
    protected $routeEdit = '{crud}Edit';
    protected $routeView = '{crud}View';
    protected $routeDelete = '{crud}Delete';

    // array(fieldname => array(),..)
    protected $fieldsEdit = array();
    protected $fieldsView = array();
    protected $fieldsList = array();

    protected $pagerLimit = 50;

    public function listAction()
    {
        if (!in_array('LIST', $this->actions)) {
            $this->app->router->redirect('default', array());
        }
        $view = new $this->viewNameList($this->app);
        $deleteForm = new $this->formNameDelete(array('form_name' => 'form_crud_delete'));
        $deleteForm->generateCsrfTocken($this->app->session->getSessionId());

        $db = $this->app->db;
        $model = new $this->modelName(array(), $db);

        // pager
        $limit = $this->app->request->getParameter('limit', $this->pagerLimit);
        $limit = ($limit > 100) ? 100 : $limit;
        $page = $this->app->request->getParameter('page', 1);
        $params = array(
            'page'  => $page,
            'limit'  => $limit,
        );
        // filter
        $filter = $this->app->request->getParameter('filter', null);
        if ($filter) {
            $params['filter'] = $filter;
        }
        $orderby = $this->app->request->getParameter('orderby', null);
        if ($orderby) {
            $params['orderby'] = $orderby;
        }

        $collection = $model->retriveCollection($params);

        $this->renderView($view, array(
            'collection' => $collection,
            'deleteForm' => $deleteForm,
            'model' => $model,
            'fields' => $this->fieldsList,
            'template_name' => $this->templateList,
            'page_name' => $this->pageNameList,
            'route_list' => $this->routeList,
            'route_view' => $this->routeView,
            'route_edit' => $this->routeEdit,
            'route_delete' => $this->routeDelete,
            'filter' => $params,
            'actions' => $this->actions,
        ));
    }

    public function editAction () {
        $view = new $this->viewNameEdit($this->app);
        $entity_id = $this->app->request->getParameter('id', 0);
        $db = $this->app->db;
        $method = $this->app->request->getMethod();
        $form = new $this->formNameEdit(array('form_name' => 'form_crud_edit'));
        $form->generateCsrfTocken($this->app->session->getSessionId());
        $flasher = new Flasher($this->app);
        $i18n = $this->app->i18n;
        $entityData = array();

        $model = new $this->modelName(array(), $db);
        if ($method === 'get') {
            if ($entity_id > 0) {
                $entityData = $model->retrieveById($entity_id);
            }
            if ($entity_id > 0 && !$entityData) {
                $flasher->add($i18n->trans('NO_ENTITY_WITH_ID', array('%ENTITY_ID%' => $entity_id)), Flasher::LVL_ERROR);
                $this->app->router->redirect($this->routeList, array());
            }
            $form->bind($entityData);
            $errs = array();
        } else {
            $formData = $this->app->request->getParameter($form->getName(), array());
            $form->bind($formData);
            $errs = $form->getErrors();
            //no errors - save
            if(empty($errs)) {
                if ($entity_id > 0 && $entity_id !== $form->getValue('id')) {
                    $flasher->add($i18n->trans('ROUTE_DOESNT_MATCH_ENTITY', array('%ENTITY_ID%' => $entity_id, '%FORM_ENTITY_ID%' =>  $formData['id'])),
                        Flasher::LVL_ALERT);
                    $this->app->router->redirect($this->routeList, array());
                }

                if ($entity_id > 0) {
                    if (!in_array('EDIT', $this->actions)) {
                        $this->app->router->redirect('default', array());
                    }
                    $entityData = $model->retrieveById($entity_id);
                    if (!$entityData) {
                        $flasher->add($i18n->trans('NO_ENTITY_WITH_ID', array('%ENTITY_ID%' => $entity_id)), Flasher::LVL_ERROR);
                        $this->app->router->redirect($this->routeList, array());
                    }
                } else {
                    if (!in_array('CREATE', $this->actions)) {
                        $this->app->router->redirect('default', array());
                    }
                }

                foreach ($this->fieldsEdit as $fieldName => $fieldData) {
                    $entityData[$fieldName] = $form->getValue($fieldName);
                }
                $model->setFieldsFromArray($entityData);

                if ($model->save() && $model->getId()) {
                    $flasher->add($i18n->trans('ENTITY_SAVED'), Flasher::LVL_NOTICE);
                    $this->app->router->redirect($this->routeEdit, array('id' => $model->getId()));
                }
                $errs['registerError'] = $i18n->trans('ENTITY_NOT_SAVED');
            }
        }

        $this->renderView($view, array(
            'page_name' => $i18n->trans('EDIT_ENTITY_ID', array('%ENTITY_ID%' => $entity_id)),
            'form' => $form,
            'model' => $model,
            'errs' => $errs,
            'fields' => $this->fieldsEdit,
            'template_name' => $this->templateEdit,
            'page_name' => $this->pageNameEdit,
            'route_list' => $this->routeList,
            'route_view' => $this->routeView,
            'route_edit' => $this->routeEdit,
            'route_delete' => $this->routeDelete,
            'actions' => $this->actions,
        ));
    }

    public function viewAction () {
        $view = new $this->viewNameEdit($this->app);
        $entity_id = $this->app->request->getParameter('id', 0);
        $db = $this->app->db;
        $method = $this->app->request->getMethod();
        //$form = new $this->formNameEdit(array('form_name' => 'form_crud_edit'));
        //$form->generateCsrfTocken($this->app->session->getSessionId());
        $flasher = new Flasher($this->app);
        $i18n = $this->app->i18n;
        $entityData = array();

        if ($method !== 'get') {
            $this->app->router->redirect($this->routeList, array());
        }
        $model = new $this->modelName(array(), $db);
        $entityData = $model->retrieveById($entity_id);
        if (!$entityData) {
            $flasher->add($i18n->trans('NO_ENTITY_WITH_ID', array('%ENTITY_ID%' => $entity_id)), Flasher::LVL_ERROR);
            $this->app->router->redirect($this->routeList, array());
        }
        $model->setFieldsFromArray($entityData);

        $this->renderView($view, array(
            'page_name' => $i18n->trans('VIEW_ENTITY_ID', array('%ENTITY_ID%' => $entity_id)),
            'fields' => $this->fieldsView,
            'model' => $model,
            'entity' => $entityData,
            'template_name' => $this->templateView,
            'page_name' => $this->pageNameView,
            'route_list' => $this->routeList,
            'route_edit' => $this->routeEdit,
            'route_view' => $this->routeView,
            'route_delete' => $this->routeDelete,
            'actions' => $this->actions,
        ));
    }

    public function deleteAction() {
        if (!in_array('DELETE', $this->actions)) {
            $this->app->router->redirect('default', array());
        }
        $entity_id = $this->app->request->getParameter('id', 0);
        $db = $this->app->db;
        $flasher = new Flasher($this->app);
        $form = new $this->formNameDelete(array('form_name' => 'form_crud_delete'));
        $form->generateCsrfTocken($this->app->session->getSessionId());
        $formData = $this->app->request->getParameter($form->getName(), array());
        $form->bind($formData);
        $errs = $form->getErrors();
        $i18n = $this->app->i18n;

        if(!empty($errs)) {
            foreach ($errs as $err) {
                $flasher->add($err, Flasher::LVL_ERROR);
            }
            $this->app->router->redirect($this->routeList, array());
        }

        $model = new $this->modelName(array(), $db);
        if ($entity_id > 0) {
            $entityData = $model->retrieveById($entity_id);
            if (!$entityData) {
                $flasher->add($i18n->trans('NO_ENTITY_WITH_ID', array('%ENTITY_ID%' => $entity_id)), Flasher::LVL_ERROR);
                $this->app->router->redirect($this->routeList, array());
            }
            $model->setFieldsFromArray($entityData);
        }

        $model->delete();
        $flasher->add($i18n->trans('ENTITY_WITH_ID_DELETED', array('%ENTITY_ID%' => $entity_id)), Flasher::LVL_NOTICE);
        $this->app->router->redirect($this->routeList, array());
    }
}
