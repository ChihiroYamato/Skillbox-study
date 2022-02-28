<?php

namespace Interfaces;

interface LoggerInterface
{
    public function logMessage(string $error) : bool;
    public function lastMessages(int $countErrors) : array|false;
}
