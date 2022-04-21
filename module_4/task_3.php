<?php

$defArr = ['a' => 'fist string', 'b' => 'second string', 'c' => 'third string',];
$simpleArr = array_keys($defArr);
$string = implode(' ', $simpleArr);

echo $string;
