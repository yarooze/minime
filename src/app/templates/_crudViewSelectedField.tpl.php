<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';
$i18n = $this->app->i18n;
$title = (isset($fieldData['title'])) ? $i18n->trans($fieldData['title']) : $fieldName;
$value = $entity[$fieldName];
if (isset($fieldData['selection']) && isset($fieldData['selection']['values'])) {
    $idField = $fieldData['selection']['idField'];
    $valueField = $fieldData['selection']['valueField'];
    foreach ($fieldData['selection']['values'] as $values) {
        if ($values[$idField] === $value) {
            $value = $values[$valueField];
            break;
        }
    }
}
?><div class="form-group row">
        <label for="<?php $view->printString($fieldName); ?>" class="col-sm-2 col-form-label"><?php $view->printString($title); ?></label>
<div class="col-sm-10">
    <div><?php $view->printString($value); ?></div>
</div>
</div>
