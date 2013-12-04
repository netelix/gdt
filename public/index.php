<?php

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
$bootstrap = require __DIR__.'/../vendor/uop/bootstrap.php';
$bootstrap->run();