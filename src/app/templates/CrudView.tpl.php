<?php $i18n = $this->app->i18n; ?>
<div class="container">
    <div>
        <?php if (in_array('LIST', $actions)): ?>
        <a class="nav-link" href="<?php echo $app->router->getUrl($route_list); ?>"><?php echo $i18n->trans('LIST_VIEW'); ?></a>
        <?php endif; ?>
    </div>

    <?php $entity_id = (int)$entity['id']; ?>
        <?php foreach ($fields as $fieldName => $fieldData) {
            $partialName = isset($fieldData['partial']) ? $fieldData['partial'] : 'crudViewField';
            $view->renderPartial($partialName, array('fieldName' => $fieldName, 'model' => $model, 'entity' => $entity, 'fieldData' => $fieldData));
        } ?>
</div>
