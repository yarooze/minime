<?php
$app->loadHelper('HtmlHelper');
$i18n = $this->app->i18n;
/*
 * @param &array   $params
 *                   'filter' => array('FIELD_MAME' => 'FIELD_VALUE') OR
 *                               array('FIELD_MAME' => array('FIELD_VALUE_1', 'FIELD_VALUE_2')) OR
 *                                   retrieve all
 *                   'orderby'   - array(array('name' => 'NAME', 'order' => 'ASC|DESC'))
 */
if (!empty($filter_cfg)) :
?>
<div class="container m-1">
<form name="crud_filter" method="get" action="<?php echo $app->router->getUrl($route_list, array()); ?>">
    <div class="form-row align-items-center"><?php
    foreach ($filter_cfg as $filterKey => $filterAttr)  {
        $title = isset($filterAttr['title']) ? $i18n->trans($filterAttr['title']) : '';
        echo '<div class="col-auto">';
        echo '<label class="sr-only" for="filter['.$filterKey.']">' . $title . '</label>';
        echo '<input type="text" name="filter['.$filterKey.']" value="';
        if (isset($filter['filter'][$filterKey])) {
            $view->printString($filter['filter'][$filterKey]);
        }
        echo '" class="form-control mb-2" placeholder="'.$title.'" /></div>';
    }
        ?><div class="col-auto">
            <button type="submit" class="btn btn-primary mb-2"><?php echo $i18n->trans('FILTER'); ?></button>
        </div>
    </div>
</form>
</div>
<?php
endif;