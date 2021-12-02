<?php

namespace Modules\Abstracts;
use Modules\Interfaces\LoggerInterface, Modules\Interfaces\EventListenerInterface;

abstract class Storage implements LoggerInterface, EventListenerInterface
{
    abstract public function create(object $object) : string|false;
    abstract public function read(string $slug) : object|false;
    abstract public function update(string $slug, object $object, object $newObject) : bool;
    abstract public function delete(string $slug) : bool;
    abstract public function list() : array|false;
}
