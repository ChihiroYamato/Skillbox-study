<?php

$defArr = [
    'alfa' => 50,
    'beta' => 60,
    'gamma' => 50,
    'delta' => 100,
    'epsilon' => 2,
];
$sizeDefArr = sizeof($defArr);

var_dump($sizeDefArr);

$newArr = array_flip($defArr);
$sizeNewArr = sizeof($newArr);

var_dump($sizeNewArr);
var_dump($sizeDefArr != $sizeNewArr);
