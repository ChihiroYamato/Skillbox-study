<?php

$randomBool = rand(0, 1);
$secondVar = ($randomBool == 0) ? null : rand(1, 3);

switch ($secondVar) {
    case null:
        echo "Переменная равна null\n";
        break;
    case 1:
        echo "Переменная равна 1\n";
        break;
    default:
        echo "Переменная имеет иное значение\n";
}

var_dump(isset($secondVar));

$thirdVar = $secondVar ?? rand(20, 30);
var_dump($thirdVar);
