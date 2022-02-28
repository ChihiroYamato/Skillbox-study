<?php
/** Объявление используемых классов из иного неймспейса*/
use \Entities\Classes\FileStorage;

/** подключение файла автозагрузки классов*/
require_once __DIR__ . '/autoload/Autoloader.php';

$storage = new FileStorage();
var_dump($storage);
