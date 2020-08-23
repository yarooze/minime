<?php
/**
 * array('partial' => 'crudListCheckboxField')
 */
$app->loadHelper('HtmlHelper');

$value = $entity[$fieldName];
?>
<td data-column="<?php $view->printString($fieldName); ?>"><?php echo checkBoxSymbol($value); ?></small></td>

