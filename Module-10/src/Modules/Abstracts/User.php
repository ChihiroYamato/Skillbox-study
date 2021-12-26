<?php

namespace Base\Modules\Abstracts;
use Base\Modules\Interfaces\EventListenerInterface;

abstract class User implements EventListenerInterface
{
    protected int $id;
    protected string $name;
    protected string $role;

    abstract protected function getTextsToEdit();
}
