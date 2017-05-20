<?php

$loader = new \Phalcon\Loader();

require APP_PATH . "/vendor/autoload.php";


if(file_exists("../public/install.php")){
    header("Location: install.php");
}

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
    	$config->application->libraryDir,
    	$config->application->testsDir
    ]
);

$loader->register();