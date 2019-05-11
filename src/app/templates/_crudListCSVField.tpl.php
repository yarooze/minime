<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';

$value = isset($entity[$fieldName]) ? $entity[$fieldName] : '';
$value = implode(', ', array_unique(explode(',', $value)));
$value = wordwrap ($value, 25);
?>
<td data-column="<?php $view->printString($fieldName); ?>"><small><pre><?php $view->printString($value ); ?></pre></small></td>

