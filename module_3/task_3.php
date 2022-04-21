<?php

$a = rand(1, 9);
$b = rand(1, 3) * 10;
var_dump($a, $b);

$c = $a * $b;
$c += rand(0, 100);

switch (true) {
    case $c >= 0 && $c < 100:
        echo '1';
        break;
    case $c >= 100 && $c < 200:
        echo '2';
        break;
    case $c >= 200 && $c < 300:
        echo '3';
        break;
    default:
        echo 'Значение больше 299';
}
