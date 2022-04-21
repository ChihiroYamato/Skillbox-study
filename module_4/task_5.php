<?php

$defArr = [
    'alfa' => 50,
    'beta' => 60,
    'gamma' => 50,
    'delta' => 100,
    'epsilon' => 20,
];
$sizeDefArr = sizeof($defArr);
var_dump($sizeDefArr);

$newArr = array_flip($defArr);
$sizeNewArr = sizeof($newArr);

var_dump($sizeNewArr);
var_dump($sizeDefArr != $sizeNewArr);

$simpleArr = array_values($newArr);
$newSimpleArr = array_merge($simpleArr, array_diff(array_keys($defArr), $simpleArr));

if (sizeof($newSimpleArr) == sizeof($defArr)) {
    $finalArray = array_combine($newSimpleArr, array_values($defArr));
    var_dump($finalArray);
}
