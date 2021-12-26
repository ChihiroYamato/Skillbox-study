<?php
/** Объявление используемых классов из иного неймспейса*/
use Base\Modules\Classes\TelegraphText,
    Base\Modules\Classes\FileStorage;

/** подключение файла автозагрузки классов*/
require_once __DIR__ . '/autoload/Autoloader.php';

$dima = new TelegraphText();
$dima->author = 'Dima';
var_dump($dima);

// $dima->slug = 'my-new_name -for|_13.slug-/45';
// echo $dima->slug . PHP_EOL;

// $dima->published = 2000000000;
// echo $dima->published . PHP_EOL;

// $dima->editText('My Name is Giovanni Giorgio, but everybody calls me Dima.');
// $dima->text = 'Погнали!';
// echo $dima->text;
