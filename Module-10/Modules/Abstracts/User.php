<?php

namespace Modules\Abstracts;
use Modules\Interfaces\EventListenerInterface;

abstract class User implements EventListenerInterface
{
    public int $id;
    public string $name;
    public string $role;

    abstract public function getTextsToEdit();
}
