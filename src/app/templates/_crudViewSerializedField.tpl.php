<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';

$i18n = $this->app->i18n;

$title = (isset($fieldData['title'])) ? $i18n->trans($fieldData['title']) : $fieldName;

is_array(unserialize($entity[$fieldName])) ? $value = implode("\n",unserialize($entity[$fieldName])) : $value = '';
?>
<div class="form-group row">
        <label for="<?php $view->printString($fieldName); ?>" class="col-sm-2 col-form-label"><?php $view->printString($title); ?></label>
<div class="col-sm-10">
    <div><pre><?php $view->printString($value); ?></pre></div>
</div>
</div>

