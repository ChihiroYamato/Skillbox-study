<?php

abstract class Storage
{
    abstract public function create(object $object) : string;
    abstract public function read(string $slug) : object ;
    abstract public function update(string $slug, object $object, object $newObject);
    abstract public function delete(string $slug);
    abstract public function list() : array;
}

abstract class View
{
    public object $storage;

    public function __construct(object $object)
    {
        $this->storage = $object;
    }
    abstract public function displayTextById(int $id);
    abstract public function displayTextByUrl(string $url);
}

abstract class User
{
    public int $id;
    public string $name;
    public string $role;

    abstract public function getTextsToEdit();
}