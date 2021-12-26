<?php
namespace Base\Modules\Abstracts;

use Base\Modules\Interfaces\LoggerInterface,
    Base\Modules\Interfaces\EventListenerInterface;

abstract class Storage implements LoggerInterface, EventListenerInterface
{
    abstract public function __construct(string $dir);
    abstract public function create(object $object) : string|false;
    abstract public function read(string $slug) : object|false;
    abstract public function update(string $slug, object $object, object $newObject) : bool;
    abstract public function delete(string $slug) : bool;
    abstract public function list() : array|false;
}
