<?php

namespace Project\Autoload;

final class Autoloader
{
    protected static array $prefixes;

    public static function register()
    {
        spl_autoload_register('self::loadClass');
    }

    public static function addNamespace(string $prefix, string $baseDir)
    {
        $prefix = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';
        self::$prefixes[$prefix][] = $baseDir;
    }

    protected static function loadClass(string $class) : bool
    {
        $prefix = $class;

        while (false !== ($pos = strrpos($prefix, '\\'))) {
            $prefix = substr($class, 0, $pos + 1);
            $relativeClass = substr($class, $pos + 1);
            $mappedFile = self::loadMappedFile($prefix, $relativeClass);

            if ($mappedFile) {
                return true;
            }
            $prefix = rtrim($prefix, '\\');
        }
        return false;
    }

    protected static function loadMappedFile(string $prefix, string $relativeClass) : bool
    {
        if (! isset(self::$prefixes[$prefix])) {
            return false;
        }
        foreach (self::$prefixes[$prefix] as $baseDir) {

            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (self::requireFile($file)) {
                return true;
            }
        }
        return false;
    }

    protected static function requireFile(string $file) : bool
    {
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }
}

Autoloader::addNamespace('Interfaces', dirname(__DIR__) . '/interfaces');
Autoloader::addNamespace('Entities', dirname(__DIR__) . '/entities');
Autoloader::register();
