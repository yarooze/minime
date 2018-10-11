<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php echo $app->config->get('charset');?>">
    <title><?php if ($page_name !== null) { $view->printString($page_name); } else { echo "Minime"; } ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
<?php $view->renderPartial('headerPartial', array()); ?>
<?php include __DIR__ . $this->template_name; ?>
<?php $view->renderPartial('footerPartial', array()); ?>
</body>
</html>