<?php

$letterSeriesArray = ['A', 'B'];
$numbersArray = [];
$formatNumbersArray = [];

for ($i = 0, $maxOption = count($letterSeriesArray)**3; $i < $maxOption; $i++) {
    /* Записываю итератор $i в строку $series в двоичном виде с заменой: {0 -> 'А' | 1 -> 'В'}  */
    $series = str_replace(['0', '1'], ['A', 'B'], sprintf("%03b", $i));

    for ($j = 0; $j < 1000; $j++) {
        /* Записываю весь номер в строку $fullNumbers */
        $fullNumbers = sprintf("%s%s%03d%s", $series[0], $series[1], $j, $series[2]);

        //echo $fullNumbers . "\n";
        $numbersArray[] = $fullNumbers;
    }
}

foreach ($numbersArray as $number) {
    var_dump($number);
}

foreach ($numbersArray as $value) {
    if ($value[2] === $value[3] && $value[3] === $value[4]) {
        $formatNumbersArray[] = $value;
    }
}

print_r($formatNumbersArray);
