<?php

namespace Modules\Abstracts;
use Modules\Interfaces\EventListenerInterface;

abstract class User implements EventListenerInterface
{
    protected int $id;
    protected string $name;
    protected string $role;

    abstract public function getTextsToEdit();
}
