<?php

$a = 9;
$b = rand(1,3) * 10;
var_dump($a, $b);

if (($a * $b) < 100) {
    echo 'меньше 100';
} elseif (($a * $b) < 200) {
    echo 'меньше 200';
} else {
    echo 'иное значение';
}
