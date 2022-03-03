<?php
/** подключение ядра */
require_once 'app/init.php';

/** Объявление используемых классов из иного неймспейса */
use App\Base\Entities\Classes\FileStorage;


$storage = new FileStorage();
var_dump($storage);
