<?php
/** Объявление используемых классов из иного неймспейса*/
use App\Base\Entities\Classes\FileStorage;

/** подключение файла автозагрузки классов*/
require_once 'vendor/autoload.php';

$storage = new FileStorage();
var_dump($storage);
