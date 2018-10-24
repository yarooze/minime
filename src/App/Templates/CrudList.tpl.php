<?php $i18n = $this->app->i18n; ?>
<div class="container">
    <table class="table">
        <thead>
        <tr>
            <?php foreach ($fields as $fieldName => $fieldData): ?>
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
<?php
if ($pager['all_pages'] > 1) :
    $plusMinusPages = 10;
    $curPage = $pager['page']; ?>
    <div class="container">
        <nav aria-label="pager">
            <ul class="pagination justify-content-center">
                <?php if ($pager['page'] - $plusMinusPages > 1) { ?>
                    <li class="page-item"><a class="page-link" href="<?php
                        echo $app->router->getUrl($route_list, array('query' => array('page' => 1))); ?>">1</a></li>
                    <li class="page-item">
                        &nbsp;&nbsp;
                    </li>
                <?php } ?>

                <?php for ($page = 1; $pager['all_pages'] >= $page; $page++ ) {
                    if ($pager['page'] - $plusMinusPages > $page) {
                        continue;
                    }
                    if ($pager['page'] + $plusMinusPages < $page) {
                        continue;
                    }
                    ?>
                    <li class="page-item<?php echo ($page == $pager['page']) ?  ' active' : ''; ?>"><a class="page-link" href="<?php
                        echo $app->router->getUrl($route_list, array('query' => array('page' => $page))); ?>"><?php
                            echo $page; ?></a></li>
                <?php } ?>

                <?php if ($pager['page'] + $plusMinusPages < $pager['all_pages']) { ?>
                    <li class="page-item">
                        &nbsp;&nbsp;
                    </li>
                    <li class="page-item"><a class="page-link" href="<?php
                        echo $app->router->getUrl($route_list, array('query' => array('page' => $pager['all_pages']))); ?>"><?php echo $pager['all_pages']; ?></a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
<?php endif;
