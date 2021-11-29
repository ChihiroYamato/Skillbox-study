<?php

require_once('abstract-classes.php');
require_once('..\Module-8\telegraph-class.php');

class FileStorage extends Storage
{
    private string $directory = '';
    private array $fileStorage = [];

    public function __construct(string $dir = 'storage')
    {
        $this->directory = __DIR__ . '\\' . basename($dir) . '\\';
        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }
    }

    public function create(object $object): string|false
    {
        if ($object instanceof TelegraphText && !str_contains($object->getSlug(), '-id=')) {
            $count = 0;
            $nameFile = $this->directory . $object->getSlug() . "-id=$count";
            while (file_exists($nameFile)) {
                $count++;
                $nameFile = $this->directory . $object->getSlug() . "-id=$count";
            }

            $object->changeSlug($nameFile);
            if (false === file_put_contents($nameFile, serialize($object))) {
                return false;
            }
            return $nameFile;
        }
        return false;
    }

    public function read(string $slug): object|false
    {
        $slug = $this->directory . basename($slug);
        if (file_exists($slug)) {
            $getObject = unserialize(file_get_contents($slug));
            if ($getObject instanceof TelegraphText) {
                return $getObject;
            }
        }
        return false;
    }

    public function update(string $slug, object $object, object $newObject): bool
    {
        $slug = $this->directory . basename($slug);
        if ($this->read($slug) == $object) {
            if (false === file_put_contents($slug, serialize($newObject))) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function delete(string $slug): bool
    {
        return unlink($this->directory . basename($slug)); 
    }

    public function list(): array|false
    {
        $this->fileStorage = [];
        if (!is_dir($this->directory)) {
            return false;
        }
        $arrayFiles = scandir($this->directory, SCANDIR_SORT_NONE);
        if (in_array('.', $arrayFiles, true)) {$arrayFiles = array_splice($arrayFiles, 2);}

        foreach ($arrayFiles as $file) {
            $scanObject = unserialize(file_get_contents($this->directory . $file));
            if($scanObject instanceof TelegraphText) {
                $this->fileStorage["$file"] = $scanObject;
            }
        }
        return $this->fileStorage;
    }

}



$storage = new FileStorage();
$johnBlack = new TelegraphText('John');
$johnBlack->editText('Hello world', 'Greating');

$johnWhite = new TelegraphText('John');

$storage->create($johnBlack);
$path = $storage->create($johnWhite);

$newJohn = $storage->read($path);
$newJohn->editText('My Name is Giovanni Giorgio, but everybody calls me Giorgio.', 'My name?');
$storage->update($path, $storage->read($path), $newJohn);


$storageArray = $storage->list();
print_r($storageArray);
echo "\n\n";

$storage->delete($path);
$storageArray = $storage->list();
print_r($storageArray);

