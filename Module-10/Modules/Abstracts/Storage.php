<?php
namespace Modules\Abstracts;

use Modules\Interfaces\LoggerInterface,
    Modules\Interfaces\EventListenerInterface;

abstract class Storage implements LoggerInterface, EventListenerInterface
{
    abstract protected function create(object $object) : string|false;
    abstract protected function read(string $slug) : object|false;
    abstract protected function update(string $slug, object $object, object $newObject) : bool;
    abstract protected function delete(string $slug) : bool;
    abstract protected function list() : array|false;
}
