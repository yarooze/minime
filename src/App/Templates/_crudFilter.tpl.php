<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';
$i18n = $this->app->i18n;
/*
 * @param &array   $params
 *                   'filter' => array('FIELD_MAME' => 'FIELD_VALUE') OR
 *                               array('FIELD_MAME' => array('FIELD_VALUE_1', 'FIELD_VALUE_2')) OR
 *                                   retrieve all
 *                   'orderby'   - array(array('name' => 'NAME', 'order' => 'ASC|DESC'))
 */
// array('model' => $model, 'route_list' => $route_list, 'filter' => $filter, 'fields' => $fields, 'filter_cfg' => $filter_cfg)
// $app->router->getUrl($route_list, array('query' => $filter));
