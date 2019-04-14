<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';

$value = $entity[$fieldName];
?>
<td data-column="<?php $view->printString($fieldName); ?>"><?php echo checkBoxSymbol($value); ?></small></td>

