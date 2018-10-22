<?php $i18n = $this->app->i18n; ?>
<div class="container">
    <div>
        <a class="nav-link" href="<?php echo $app->router->getUrl($route_list); ?>"><?php echo $i18n->trans('LIST_VIEW'); ?></a>
    </div>

    <?php $entity_id =  (int)$form->getValue('id'); ?>
    <form method="post" id="<?php echo $form->getName(); ?>" name="<?php echo $form->getName(); ?>"
          action="<?php echo $app->router->getUrl($route_edit, array('id' => $entity_id)); ?>" >

        <?php if($errs):?>
            <div class="form-group">
                <?php foreach($errs as $err): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><?php echo $i18n->trans('ERROR'); ?></strong> <?php echo $i18n->trans($err); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="<?php echo $i18n->trans('CLOSE'); ?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php foreach ($fields as $fieldName => $fieldData) {
            $partialName = isset($fieldData['partial']) ? $fieldData['partial'] : 'crudEditField';
            $view->renderPartial($partialName, array('fieldName' => $fieldName, 'form' => $form, 'fieldData' => $fieldData));
        } ?>
        <input name="<?php echo $form->getFullFieldName('csrf_tocken'); ?>"
               value="<?php $view->printString($form->getCsrfTocken()); ?>" type="hidden" />
        <button type="submit" class="btn btn-primary"><?php echo $i18n->trans('SAVE_ENTITY'); ?></button>
    </form>
</div>
