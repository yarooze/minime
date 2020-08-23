<?php
namespace app\controller;

use app\core\I18n;
use app\view\DefaultView as DefaultView,
    app\view\HtmlView as HtmlView,
    app\view\JsonView as JsonView,
    app\form\RegisterForm as RegisterForm,
    app\service\ApidocService;

/**
 *
 * @author jb
 */
Class ApiController extends BaseController
{
     /**
     * @Api(name="api_doc", method="get", action_url="api/getapidoc", _comment="This page")
     * @throws \ReflectionException
     */
    public function apidocAction() {
        $view = new JsonView($this->app);
        $data = $this->app->request->getParameters();

        /** @var I18n $i18n */
        $i18n = $this->app->i18n;
        $data['i18n'] = $i18n->trans('MSG');

        $host = $this->app->router->getHost();
        $protocol = $this->app->router->getProtocol();

        $content = ApidocService::generateApidocForClassFile(__CLASS__, $protocol . '://' . $host);

        $params = array(
            'data' => $content,
        );

        $view->render($params);
    }
}
