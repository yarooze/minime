<?php
$app->loadHelper('HtmlHelper');
$i18n = $this->app->i18n;
$title = (isset($fieldData['title'])) ? $i18n->trans($fieldData['title']) : $fieldName;

?><div class="form-group row">
    <label for="<?php $view->printString($fieldName); ?>" class="col-sm-2 col-form-label"><?php $view->printString($title); ?></label>
    <div class="col-sm-10">
        <div><?php if ($entity[$fieldName]):
                $tags = explode(',', $entity[$fieldName]);
            foreach($tags as $tag): ?>
                <span class="badge badge-info"><?php $view->printString($tag); ?></span><?php
            endforeach; ?>
        <?php endif; ?></div>
    </div>
</div>

