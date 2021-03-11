<?php
    $ClassLoader = new Nette\Loaders\RobotLoader;
    $ClassLoader->setAutoRefresh(true);
    $ClassLoader->addDirectory(__DIR__ . '/lib');
    $ClassLoader->setTempDirectory(__DIR__ . '/temp');
    $ClassLoader->register();
?>