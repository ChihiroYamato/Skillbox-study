<?php

use Modules\Classes\FileStorage,
    Modules\Classes\TelegraphText;

require_once __DIR__ . '/Autoload/autoloader.php';

$callBack = function() {
    echo 'Идет прослушивание метода!' . PHP_EOL;
};

$text = new TelegraphText('john');
$text->editText('Hello World!', 'Greating');

TelegraphText::$storage->attachEvent('create' , $callBack);
$pathText = TelegraphText::$storage->create($text);
echo $pathText . PHP_EOL;
TelegraphText::$storage->detouchEvent('create');


TelegraphText::$storage->logMessage('Error_404');
TelegraphText::$storage->logMessage('Error_505');
TelegraphText::$storage->logMessage('Error_500');
TelegraphText::$storage->logMessage('Error_405');
TelegraphText::$storage->logMessage('Error_400');
TelegraphText::$storage->logMessage('Error_555');
$logs = TelegraphText::$storage->lastMessages(3);
var_dump($logs);
