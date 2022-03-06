<?php

namespace App\Base\Skillbox\Entities;

abstract class View
{
    public Storage $storage;

    public function __construct(object $object)
    {
        $this->storage = $object;
    }
    abstract public function displayTextById(int $id);
    abstract public function displayTextByUrl(string $url);
}
