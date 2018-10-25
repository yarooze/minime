<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';
$title = (isset($entity['title'])) ? $i18n->trans($entity['title']) : $fieldName;
?><div class="form-group row">
        <label for="<?php $view->printString($fieldName); ?>" class="col-sm-2 col-form-label"><?php $view->printString($title); ?></label>
<div class="col-sm-10">
    <div><?php $view->printString($entity[$fieldName]); ?></div>
</div>
</div>

