<?php
/* загрузка алфавита из отдельного файла в переменную. (по стандарту алфавит из задания)  */
$alphabet = file_get_contents('alphabet.txt', false, null, 0, 512);
if ($alphabet === false) {
    exit('Файл не существует');
}
/* Формирование массива с лишними знаками для фильтра переменной алфавита */
$arrayWasteSigns = [
    mb_chr(32), // ' '
    mb_chr(13), // '\r'
    mb_chr(10), // '\n'
    ';',
    '.',
    ',',
    '/',
    '|',
    ':',
    '\\',
    '?',
    '!',
];
/* применение фильтра к алфавиту и перевод в нижний регистр */
$alphabet = strtolower(str_replace($arrayWasteSigns, '', $alphabet));
/* подсчет мощности алфавита */
$alphabetCount = mb_strlen($alphabet) - 1;

/* Инициализация основных переменных для задания */
$defString = 'Respice post te. Hominem te memento!';    // Шифруемая фраза
$cryptoString = '';                                     // Результат шифрования
$decryptString = '';                                    // Результат дешифрования
$cryptoShift = 3;                                       // Сдвиг шифра

for ($char = 0, $stringCount = mb_strlen($defString); $char < $stringCount; $char++) {
    /* Если символ не является буквой - шифр не применяется */
    if (in_array($defString[$char], $arrayWasteSigns) === true) {
        $cryptoString .= $defString[$char];
        continue;
    }
    /* Шифр применяется по заданному алфавиту, а не по смещению байта в ASCI*/
    $cryptoString .= $alphabet[(stripos($alphabet, $defString[$char]) + $cryptoShift) % $alphabetCount];
}

var_dump($cryptoString);

for ($char = 0, $stringCount = mb_strlen($cryptoString); $char < $stringCount; $char++) {
    if (in_array($cryptoString[$char], $arrayWasteSigns) === true) {
        $decryptString .= $cryptoString[$char];
        continue;
    }
    $decryptString .= $alphabet[(stripos($alphabet, $cryptoString[$char]) + $alphabetCount - $cryptoShift) % $alphabetCount];
}

var_dump($decryptString);

