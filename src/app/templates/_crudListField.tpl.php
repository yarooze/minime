<td data-column="<?php $view->printString($fieldName); ?>"><?php 

$title = (isset($fieldData['title'])) ? $i18n->trans($fieldData['title']) : $fieldName;
if (!empty($fieldData['raw'])) {
    echo $entity[$fieldName];
} else {
	$view->printString($entity[$fieldName]); 
}
?></td>