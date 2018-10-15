<?php $i18n = $this->app->i18n; ?>
<div class="container">
    <?php $entity_id =  (int)$form->getValue('id'); ?>
    <form method="post" id="<?php echo $form->getName(); ?>" name="<?php echo $form->getName(); ?>"
          action="<?php echo $app->router->getUrl($route_edit, array('id' => $entity_id)); ?>" >

        <?php if($errs):?>
            <div class="form-group">
                <?php foreach($errs as $err): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><?php echo $i18n->trans('ERROR'); ?></strong> <?php $i18n->trans($err); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="<?php $i18n->trans('CLOSE'); ?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif;?>

        <?php foreach ($fields as $field): ?>

        <div class="form-group row">
            <label for="<?php $view->printString($field); ?>" class="col-sm-2 col-form-label"><?php $view->printString($field); ?></label>
            <div class="col-sm-10">
                <input type="text" class="form-control"
                       name="<?php echo $form->getFullFieldName($field); ?>"
                       value="<?php $view->printString($form->getValue($field)); ?>">
            </div>
        </div>
        <?php endforeach; ?>
        <input name="<?php echo $form->getFullFieldName('csrf_tocken'); ?>"
               value="<?php $view->printString($form->getCsrfTocken()); ?>" type="hidden" />
        <button type="submit" class="btn btn-primary"><?php $i18n->trans('SAVE'); ?></button>
    </form>
</div>
