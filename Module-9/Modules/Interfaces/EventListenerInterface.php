<?php

namespace Modules\Interfaces;

interface EventListenerInterface
{
    public function attachEvent(callable $method, callable $callbackFun);
    public function detouchEvent(callable $method);
}
