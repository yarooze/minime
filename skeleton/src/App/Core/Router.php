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
//        $routeCredentials = $this->getRouteCredentials();
//        $isFreeRoute = $this->isFreeRoute();
//
//        $route_name = $this->getCurrentRouteName();
//        // Every route must have credentials or it is blocked
//        if (empty($routeCredentials)) {
//            if($this->app->config->get('env') === 'dev') {
//                throw new UnknownRouteException('The credentials for the route ['.$route_name.'] is not defined!');
//            } else {
//                $this->redirect('logout', array());
//            }
//        }
//
//        if ($isFreeRoute) {
//            return;
//        }
//
//        // Block not authenticated fully users for the 'not free' routes
//        if (!$this->app->auth->isAuthenticated(SimpleAuth::IS_AUTHENTICATED_FULLY)) {
//            if($this->app->config->get('env') === 'dev') {
//                throw new MinimeException("Not fully authenticated!");
//            } else {
//                $this->redirect('logout', array());
//            }
//        }
//
//        $user = $this->app->session->get('user');
//        foreach ($routeCredentials as $routeCredential) {
//            if (!$user->hasCredential($routeCredential)) {
//                if($this->app->config->get('env') === 'dev') {
//                    throw new MinimeException('This user has not enough credentials ['.$routeCredential.']!');
//                } else {
//                    $this->redirect('default', array());
//                }
//            }
//        }
    }
}
