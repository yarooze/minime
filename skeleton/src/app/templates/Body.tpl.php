<h1><?php $view->printString('Data'); ?>:</h1>
<?php
  $view->renderPartial('myPartial', array('data'=>$data));
?>