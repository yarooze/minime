<?php
$app->loadHelper('HtmlHelper');
$i18n = $this->app->i18n;
$title = (isset($fieldData['title'])) ? $i18n->trans($fieldData['title']) : $fieldName;
?><div class="form-group row">
        <label for="<?php $view->printString($fieldName); ?>" class="col-sm-2 col-form-label"><?php $view->printString($title); ?></label>
<div class="col-sm-10">
    <div><?php    
if (!empty($fieldData['raw'])) {
    echo $entity[$fieldName];
} else {
	$view->printString($entity[$fieldName]); 
}    
?></div>
<iframe src="<?php echo $entity[$fieldName]; ?>" style="<?php echo (!empty($fieldData['iframe_style'])) ? $fieldData['iframe_style'] : '' ?>"></iframe>
</div>
</div>
