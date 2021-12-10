<?php
/** Объявление используемых классов из иного неймспейса*/
use Modules\Classes\TelegraphText,
    Modules\Classes\FileStorage;

/** подключение файла автозагрузки классов*/
require_once __DIR__ . '/Autoload/autoloader.php';

$dima = new TelegraphText();
$dima->author = 'Dima';

$dima->slug = 'my-new_name -for|_13.slug-/45';
echo $dima->slug . PHP_EOL;

$dima->published = 2000000000;
echo $dima->published . PHP_EOL;

$dima->editText('My Name is Giovanni Giorgio, but everybody calls me Dima.');
$dima->text = 'Погнали!';
echo $dima->text;
