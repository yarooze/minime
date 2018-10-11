<?php
try {
    $app = require __DIR__ . '/../src/bootstrap.php';
    $app->run();
} catch (Exception $e) {
    $app->logger->log(array(date('Y.m.d H:i:s'), $e->getMessage(), $e));
    if ($app->config->get('env') == 'dev') {
        throw $e;
    } else {
        echo 'sorry \(T_T)/';
        //echo $e->getMessage();
    }
}