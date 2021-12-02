<?php


$a = new TelegraphText('Alex');
/*TelegraphText::$storage->logMessage('Error_404');
TelegraphText::$storage->logMessage('Error_505');
TelegraphText::$storage->logMessage('Error_500');
TelegraphText::$storage->logMessage('Error_405');
TelegraphText::$storage->logMessage('Error_400');
TelegraphText::$storage->logMessage('Error_555');*/
$b = TelegraphText::$storage->lastMessages(3);
var_dump($b);



//тестирование работоспособности кода пункты 4-9 (раскоментировать для выполнения)
/*
$storage = new FileStorage();
$johnBlack = new TelegraphText('John');
$johnBlack->editText('Hello world', 'Greating');

$johnWhite = new TelegraphText('John');

$storage->create($johnBlack);
$path = $storage->create($johnWhite);

$newJohn = $storage->read($path);
$newJohn->editText('My Name is Giovanni Giorgio, but everybody calls me Giorgio.', 'My name?');
$storage->update($path, $storage->read($path), $newJohn);


$storageArray = $storage->list();
print_r($storageArray);
echo "\n\n";

$storage->delete($path);
$storageArray = $storage->list();
print_r($storageArray);
*/

//Тестирование модификации класса TelegraphText, пункт 10 (раскоментировать для выполнения)
/*
$a = new TelegraphText('Alex');
$b = new TelegraphText('Dima');
$c = new TelegraphText('Naruto');

$a->editText('Good', 'morning');
$b->editText('hello', 'world');

$pathofA = TelegraphText::$storage->create($a);
$pathofB = $b->storeText();
$a::$storage->create($c);

$c->loadText($pathofA);
$a = TelegraphText::$storage->read($pathofB);

var_dump($a, $c);


$storageArray = TelegraphText::$storage->list();
print_r($storageArray);
*/
