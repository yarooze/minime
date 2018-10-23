<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php echo $app->config->get('charset');?>">
    <title><?php if ($page_name !== null) { $view->printString($page_name); } else { echo "Minime"; } ?></title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

</head>
<body>
<?php $view->renderPartial('headerPartial', array()); ?>
<?php include __DIR__ . $this->template_name; ?>
<?php $view->renderPartial('footerPartial', array()); ?>
</body>
</html>