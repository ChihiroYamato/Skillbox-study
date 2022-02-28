<?php

namespace Entities\Classes;
use \Interfaces\EventListenerInterface;

abstract class User implements EventListenerInterface
{
    protected int $id;
    protected string $name;
    protected string $role;

    abstract protected function getTextsToEdit();
}
