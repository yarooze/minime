<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';

is_array(json_decode($entity[$fieldName], true)) ? $value = implode("\n",json_decode($entity[$fieldName], true)) : $value = '';
$value = wordwrap (str_replace(',', ', ', $value), 25);
?>
<td data-column="<?php $view->printString($fieldName); ?>"><small><pre><?php $view->printString($value); ?></pre></small></td>
