<?php

use Modules\Classes\FileStorage,
    Modules\Classes\TelegraphText;

require_once __DIR__ . '/Autoload/autoloader.php';

$callBack = function() {
    echo 'Идет прослушивание метода!' . PHP_EOL;
};

$test = new TelegraphText('john');
TelegraphText::$storage->attachEvent('getValue' , $callBack);
TelegraphText::$storage->getValue('Alex', 19);
TelegraphText::$storage->detouchEvent('getValue');
TelegraphText::$storage->getValue('Dima', 24);







// $text = new TelegraphText('John');
// $banl = new FileStorage();
// print_r($text)

// $a = new TelegraphText('Alex');
// TelegraphText::$storage->logMessage('Error_404');
// TelegraphText::$storage->logMessage('Error_505');
// TelegraphText::$storage->logMessage('Error_500');
// TelegraphText::$storage->logMessage('Error_405');
// TelegraphText::$storage->logMessage('Error_400');
// TelegraphText::$storage->logMessage('Error_555');
// $b = TelegraphText::$storage->lastMessages(3);
// var_dump($b);
