<?php

namespace app\core;

use app\exception\MinimeException;
use app\exception\UnknownRouteException;
use app\security\MinimeAuth;

/**
 *
 * @author jb
 *
 */
Class MinimeRouter
{
    /**
     * REQUEST_URI
     * @var string
     */
    protected $request_uri = null;
    /**
     * @var \App\Application
     */
    protected $app = null;
    /**
     * app routing from the config
     * @var array
     *
     */
    protected $routing = array();
    /**
     *
     * @var array
     */
    protected $current_route = null;
    /**
     * @var string
     */
    protected $current_route_name = null;

    public function __construct(\App\Application $app)
    {
        $this->app = $app;
        $this->routing = $app->cfg['routing'];
        $this->matchRoute();
    }

    protected function matchRoute()
    {
        //prepare uri parts
        $request_uri = $this->app->request->getUri();
        $request_method = $this->app->request->getMethod();
        $this->request_uri = $request_uri;

        if (strpos($request_uri, '?')) {
            $request_uri = substr($request_uri, 0, strpos($request_uri, '?'));
        }

        if (strpos($request_uri, '.')) {
            $dotpos = strrpos($request_uri, '.');
            $current_format = substr($request_uri, $dotpos + 1);
            $request_uri = substr($request_uri, 0, $dotpos);
            $this->app->request->setFormat($current_format);
        }

        $route_parts = explode('/', $request_uri);
        foreach ($route_parts as $key => $part) {
            if (trim($part) == '') {
                unset($route_parts[$key]);
            }
        }
        $route_parts = array_values($route_parts);

        //match route
        $current_route = null;
        foreach ($this->routing as $route_name => $route_params) {
            //method matches?
            if (!empty($route_params['methods']) && !in_array($request_method, $route_params['methods'])) {
                //var_dump('Wrong method: '.$request_method);
                continue;
            }

            //format matches?
            $current_format = $this->app->request->getFormat();
            if (!empty($route_params['formats']) && !in_array($current_format, $route_params['formats'])) {
                //var_dump('Wrong format: '.$this->current_format);
                continue;
            }

            //route part matches
            if (!$this->routePartsMatch($route_parts, $route_params['url'])) {
                //var_dump(array('Wrong path for: '.$route_name, $route_parts, $route_params['url']));
                continue;
            } else {//match
                $this->current_route_name = $route_name;
                $this->current_route = $this->routing[$route_name];
                return true;
            }
        }
        return false;
    }

    /**
     * tests if this url matches given route
     * @param array $url_parts - current url
     * @param array $routing_url_params - parameter from the app's routing
     * @return bool
     */
    public function routePartsMatch($url_parts, $routing_url_params)
    {
        if (count($url_parts) !== count($routing_url_params)) {
            return false;
        }

        $matched = ($url_parts == $routing_url_params);
        $params = array();

        foreach ($routing_url_params as $index => $routing_part) {
            if (!array_key_exists($index, $url_parts)) {
                //var_dump(array('url to short',$url_parts,$routing_url_params));
                $matched = false;
            } elseif (is_array($routing_part)) {
                $matched = (bool)preg_match('/^' . $routing_part['regexp'] . '$/', $url_parts[$index]);
                if ($matched) {
                    $params[$routing_part['name']] = $url_parts[$index];
                }
            } else {
                $matched = ($routing_part == $url_parts[$index]);
            }

            if (!$matched) {
                return false;
            }
        }

        foreach ($params as $p_name => $p_value) {
            $this->app->request->setParameter($p_name, $p_value);
        }
        return $matched;
    }

    /**
     * returns cur route name (or null, if none matches)
     * @return string
     */
    public function getCurrentRouteName()
    {
        return $this->current_route_name;
    }

    /**
     * current controller name
     */
    public function getControllerName()
    {
        return $this->current_route['controller'];
    }

    /**
     * current action name
     */
    public function getActionName()
    {
        return $this->current_route['action'];
    }

    public function getRouteByName($routeName)
    {
        $route = null;
        if (array_key_exists($routeName, $this->routing)) {
            $route = $this->routing[$routeName];
        }
        return $route;
    }

    /**
     * @return array
     */
    public function getRouteCredentials()
    {
        return (isset($this->current_route['credentials'])) ? $this->current_route['credentials'] : array();
    }

    /**
     * If this route has '*' as credentials - is free for ALL users, even not authenticated fully users
     * @return bool
     */
    public function isFreeRoute()
    {
        $credentials = $this->getRouteCredentials();
        return in_array('*', $credentials);
    }

    public function getUrl($routeName, $params = array(), $global = false)
    {

        $route = $this->getRouteByName($routeName);
        if ($route == null) {
            throw new MinimeException('Unknown route [' . $routeName . '] [' . $this->app->request->getUri() . '] !');
        }
        $location = '';
        foreach ($route['url'] as $urlPart) {
            if (is_array($urlPart)) {
                $urlPart = $params[$urlPart['name']];
            }
            $location .= '/' . $urlPart;
        }
        if (array_key_exists('format', $params)) {
            $format = $params['format'];
            $location .= '.' . $format;
        }
        if (!isset($format) || empty($format)) {
            foreach ($route['formats'] as $format) {
                $location .= '.' . $format;
                break;
            }
        }

        if (array_key_exists('query', $params)) {
            $location .= '?' . http_build_query($params['query']);
        }

        if ($global) {
            $location = "http://" . $_SERVER['HTTP_HOST'] . $location;
        }

        return $location;
    }

    public function redirect($routeName, $params = array())
    {

        $location = $this->getUrl($routeName, $params, true);

        header("Location: " . $location);
        //header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
        die();
    }

    public function checkRouteCredentials() {

        $routeCredentials = $this->getRouteCredentials();
        $isFreeRoute = $this->isFreeRoute();

        $route_name = $this->getCurrentRouteName();
        // Every route must have credentials or it is blocked
        if (empty($routeCredentials)) {
            if($this->app->config->get('env') === 'dev') {
                throw new UnknownRouteException('The credentials for the route [' . $routeName . '] [' . $this->app->request->getUri() . '] is not defined!');
            } else {
                $this->redirect('logout', array());
            }
        }

        if ($isFreeRoute) {
            return;
        }

        // Block not authenticated fully users for the 'not free' routes
        if (!$this->app->auth->isAuthenticated(MinimeAuth::IS_AUTHENTICATED_FULLY)) {
            if($this->app->config->get('env') === 'dev') {
                throw new MinimeException("Not fully authenticated!");
            } else {
                $this->redirect('logout', array());
            }
        }

        $user = $this->app->session->get('user');
        foreach ($routeCredentials as $routeCredential) {
            if (!$user->hasCredential($routeCredential)) {
                if($this->app->config->get('env') === 'dev') {
                    throw new MinimeException('This user has not enough credentials ['.$routeCredential.']!');
                } else {
                    $this->redirect('default', array());
                }
            }
        }

    }
}
