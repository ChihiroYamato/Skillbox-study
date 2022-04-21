<?php

$letterSeriesArray = ['A', 'B'];
$numbersArray = [];
$seriesString = '';

foreach ($letterSeriesArray as $seriesString[0]) {
    foreach ($letterSeriesArray as $seriesString[1]) {
        foreach ($letterSeriesArray as $seriesString[2]) {
            for ($j = 0; $j < 1000; $j++) {
                /* Записываю весь номер в строку $fullNumbers */
                $fullNumbers = sprintf("%s%s%03d%s", $seriesString[0], $seriesString[1], $j, $seriesString[2]);

                //echo $fullNumbers . "\n";
                $numbersArray[] = $fullNumbers;
            }
            /* Конструкции if() в конце каждого цикла поочередно очищают строку от последнего символа */
            if ($seriesString[2] === $letterSeriesArray[array_key_last($letterSeriesArray)]) {
                $seriesString = substr($seriesString, 0, -1);
            }
        }
        if ($seriesString[1] === $letterSeriesArray[array_key_last($letterSeriesArray)]) {
            $seriesString = substr($seriesString, 0, -1);
        }
    }
    if ($seriesString[0] === $letterSeriesArray[array_key_last($letterSeriesArray)]) {
        $seriesString = substr($seriesString, 0, -1);
    }
}

foreach ($numbersArray as $number) {
    var_dump($number);
}

foreach ($numbersArray as $key => &$value) {
    $value = substr($value, 2, 3);
    if ($value[0] !== $value[1] || $value[1] !== $value[2]) {
        array_splice($numbersArray, $key, 1);
    }
}

print_r($numbersArray);