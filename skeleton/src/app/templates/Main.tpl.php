<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php echo $app->config->get('charset');?>">
    <title><?php if ($page_name !== null) { $view->printString($page_name); } else { echo "Mesa"; } ?></title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <script src="<?php echo PATH_TO_WEB_DIR; ?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo PATH_TO_WEB_DIR; ?>/assets/js/popper.min.js"></script>
    <script src="<?php echo PATH_TO_WEB_DIR; ?>/assets/js/bootstrap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo PATH_TO_WEB_DIR; ?>/favicon.ico">
</head>
<body>
<?php $view->renderPartial('headerPartial', array('user' => $this->app->session->get('user'))); ?>
<div class="container">
    <?php $view->renderTemplate($this->template_name, $params); ?>
</div>
<?php $view->renderPartial('footerPartial', array()); ?>
</body>
</html>