<?php
namespace App\Core;

use App\Exception\MinimeException;

/**
 *
 * @author jb
 *
 */
Class Router extends MinimeRouter
{
    public function checkRouteCredentials() {
// Example code.
//        $freeRoutes = array('login', 'logout', 'default');
//        $adminRoutes = array('userList', 'userEdit', 'userDelete');
//
//        $whitelist = array_merge($freeRoutes, $adminRoutes);
//
//        $route_name = $this->getCurrentRouteName();
//        if (!in_array($route_name, $whitelist)) {
//            if($this->app->config->get('env') === 'dev') {
//                throw new UnknownRouteException('The route is not in the whitelist');
//            } else {
//                $this->redirect('logout', array());
//            }
//        }
//
//        if (!in_array($route_name, $freeRoutes) && !$this->app->auth->isAuthenticated(SimpleAuth::IS_AUTHENTICATED_FULLY)) {
//            if($this->app->config->get('env') === 'dev') {
//                throw new MinimeException("Not fully authenticated!");
//            } else {
//                $this->redirect('logout', array());
//            }
//        }
//
//        $user = $this->app->session->get('user');
//        if (in_array($route_name, $adminRoutes) && !$user->hasCredential('ADMIN_CLIENT')) {
//            if($this->app->config->get('env') === 'dev') {
//                throw new MinimeException("Not enough credentials! Must be admin.");
//            } else {
//                $this->redirect('default', array());
//            }
//        }
    }
}
