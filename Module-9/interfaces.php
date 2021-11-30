<?php

interface LoggerInterface
{
    public function logMessage(string $error);
    public function lastMessages(int $countErrors);
}

interface EventListenerInterface
{
    public function attachEvent(callable $method, callable $callbackFun);
    public function detouchEvent(callable $method);
}