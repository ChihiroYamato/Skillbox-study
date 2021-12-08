<?php
/** Объявление используемых классов из иного неймспейса*/
use Modules\Classes\TelegraphText,
    Modules\Classes\FileStorage;

/** подключение файла автозагрузки классов*/
require_once __DIR__ . '/Autoload/autoloader.php';

/** Реализация callback функции*/
$callBack = function() {
    echo 'Идет прослушивание метода!' . PHP_EOL;
};

/** Создание тестового класса */
$textStorage = new FileStorage('text_storage');
$text = new TelegraphText('john');
$text->editText('Hello World!', 'Greating');

/** Проверка прослушки ивента*/
$textStorage->attachEvent('create' , $callBack);
$pathText = $textStorage->create($text);
echo $pathText . PHP_EOL;
$textStorage->detouchEvent('create');

/** Проверка записи логов*/
$textStorage->logMessage('Error_404');
$textStorage->logMessage('Error_505');
$textStorage->logMessage('Error_500');
$textStorage->logMessage('Error_405');
$textStorage->logMessage('Error_400');
$textStorage->logMessage('Error_555');
$logs = $textStorage->lastMessages(3);
var_dump($logs);
