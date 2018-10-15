<?php $i18n = $this->app->i18n; ?>
<div class="container">
        <table class="table">
            <thead>
            <tr>
                <?php foreach (fields as $field): ?>
                <th scope="col"><?php $view->printString($field); ?></th>
                <?php endforeach ?>
                <th>
                    <a href="<?php echo $app->router->getUrl($routeEdit, array('id' => 0)); ?>">+</th>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($collection as $entity): ?>
            <tr>
                <?php foreach (fields as $field): ?>
                    <td data-column="<?php $view->printString($field); ?>"><?php $view->printString($entity[$field]); ?></td>
                <?php endforeach; ?>
                <td data-column="edit">
                    <a class="btn btn-success"
                       href="<?php echo $app->router->getUrl($routeEdit, array('id' => $entity['id'])); ?>"><?php echo $i18n->trans('EDIT'); ?></a></td>
                </td>
                <td data-column="delete">
                    <form name="<?php echo $deleteForm->getName(); ?>" method="post"
                          action="<?php echo $app->router->getUrl($routeDelete, array('id' => $entity['id'])); ?>">
                        <input name="<?php echo $deleteForm->getFullFieldName('csrf_tocken'); ?>"
                               value="<?php $view->printString($deleteForm->getCsrfTocken()); ?>" type="hidden" />
                        <input class="btn btn-danger" name="action" value="<?php echo $i18n->trans('DELETE'); ?>"
                               onclick="return confirm('<?php echo $i18n->trans('CONFIRM_USER_DELETE'); ?>');" type="submit">
                    </form>
                </td>
            <?php endforeach;  ?>
            </tr>
            </tbody>
        </table>
</div>
