<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';
$i18n = $this->app->i18n;
$title = (isset($fieldData['title'])) ? $i18n->trans($fieldData['title']) : $fieldName;

?><div class="form-group row">
    <label for="<?php $view->printString($fieldName); ?>" class="col-sm-2 col-form-label"><?php $view->printString($title); ?></label>
    <div class="col-sm-10">
        <div><?php if ($entity[$fieldName]):?>
                <pre><?php
                    echo wordwrap(json_encode(json_decode($entity[$fieldName]), JSON_PRETTY_PRINT), 100); ?></pre>
        <?php endif; ?></div>
    </div>
</div>

