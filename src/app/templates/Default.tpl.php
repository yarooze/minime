<!DOCTYPE html>
<html>
<head>
  <meta charset="<?php echo $app->config->get('charset');?>">
  <title>Minime</title>
</head>
<body>
<h1><?php $view->printString('Data'); ?>:</h1>
<?php
  $view->renderPartial('myPartial', array('data' => $data));
?>
</body>
</html>