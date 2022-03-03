<?php

namespace App\Base\Interfaces;

interface LoggerInterface
{
    public function logMessage(string $error) : bool;
    public function lastMessages(int $countErrors) : array|false;
}
