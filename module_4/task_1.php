<?php

$fistString = 'Лето пришло';
$varPos = strpos($fistString, ' ');
var_dump($varPos);

$subString = substr($fistString, $varPos + 1);
var_dump($subString);
