<?php $i18n = $this->app->i18n; ?>
<div class="container">
    <table class="table">
        <thead>
        <tr>
            <?php foreach ($fields as $fieldName => $fieldData): ?>
            <?php $fieldName = (isset($fieldData['title'])) ? $i18n->trans($fieldData['title']) : $fieldName; ?>
                <th scope="col"><?php $view->printString($fieldName); ?></th>
            <?php endforeach ?>
            <th>
                <?php if (in_array('CREATE', $actions)): ?>
                <a class="btn btn-primary"
                   href="<?php echo $app->router->getUrl($route_edit, array('id' => 0)); ?>"><?php echo $i18n->trans('NEW_ENTITY'); ?>
                <?php endif; ?>
            </th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <?php $view->renderPartial('crudFilter', array('model' => $model, 'route_list' => $route_list, 'filter' => $filter, 'fields' => $fields, 'filter_cfg' => $filter_cfg)); ?>
        <tbody>
        <?php foreach ($collection as $entity): ?>
        <tr>
            <?php foreach ($fields as $fieldName => $fieldData) {
                $partialName = isset($fieldData['partial']) ? $fieldData['partial'] : 'crudListField';
                $view->renderPartial($partialName, array('fieldName' => $fieldName, 'entity' => $entity, 'fieldData' => $fieldData));
            } ?>
            <td data-column="view">
                <?php if (in_array('VIEW', $actions)): ?>
                <a class="btn btn-info"
                   href="<?php echo $app->router->getUrl($route_view, array('id' => $entity['id'])); ?>"><?php echo $i18n->trans('VIEW'); ?></a>
                <?php endif; ?>
            </td>
            <td data-column="edit">
                <?php if (in_array('EDIT', $actions)): ?>
                <a class="btn btn-success"
                   href="<?php echo $app->router->getUrl($route_edit, array('id' => $entity['id'])); ?>"><?php echo $i18n->trans('EDIT'); ?></a>
                <?php endif; ?>
            </td>
            <td data-column="delete">
                <?php if (in_array('DELETE', $actions)): ?>
                <form name="<?php echo $deleteForm->getName(); ?>" method="post"
                      action="<?php echo $app->router->getUrl($route_delete, array('id' => $entity['id'])); ?>">
                    <input name="<?php echo $deleteForm->getFullFieldName('csrf_tocken'); ?>"
                           value="<?php $view->printString($deleteForm->getCsrfTocken()); ?>" type="hidden" />
                    <input class="btn btn-danger" name="action" value="<?php echo $i18n->trans('DELETE'); ?>"
                           onclick="return confirm('<?php echo $i18n->trans('CONFIRM_DELETE'); ?>');" type="submit">
                </form>
                <?php endif; ?>
            </td>
            <?php endforeach;  ?>
        </tr>
        </tbody>
    </table>
</div>
<?php $view->renderPartial('crudPager', array('route_list' => $route_list, 'filter' => $filter)); ?>
