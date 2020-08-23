<?php
$partialName = (isset($partial_name)) ? $partial_name : 'myPartial';
if($app->config->get('env') === 'dev' && isset($data['test'])) { ?>
    <h1><?php $view->printString('Data'); ?>:</h1>
    <?php
    $view->renderPartial($partialName, array('data'=>$data));
} else {
    echo '<h1>XXXXX!</h1>';
}