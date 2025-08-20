<?php
try {
    $app = require __DIR__ . '/../src/bootstrap.php';
    $app->run();
} catch (\Throwable $e) {
    $app->logger->log(array(date('Y.m.d H:i:s'), $e->getMessage(), $e));
    if ($app->config->get('env') == 'dev') {
        echo '<pre>';
        print_r($e);
        echo '</pre>';
    } else {
        echo 'sorry \(T_T)/';
        //echo $e->getMessage();
    }
}