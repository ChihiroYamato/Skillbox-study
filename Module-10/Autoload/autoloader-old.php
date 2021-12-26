<?php

spl_autoload_register(function ($class) {
    $prefix = 'Base\\';
    $baseDir = dirname(__DIR__) . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativePath = substr($class, $len);

    $path = $baseDir . str_replace('\\', '/', $relativePath) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});
