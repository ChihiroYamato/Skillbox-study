<?php

$startDeposit = $currentDeposit = 100000;
$rate = 0.08;
$years = 0;

while (true) {
    if ($currentDeposit >= $startDeposit * 2) {
        break;
    }

    $currentDeposit *= (1 + $rate);
    $years++;

    if ($years % 3 === 0) {
        $rate += 0.02;
    }
}
$currentDeposit = round($currentDeposit, 2);
echo "Через $years лет депозит в $startDeposit удвоится (точное значение итогового депозита: $currentDeposit)\n";

