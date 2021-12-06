<?php

require_once('abstract-classes.php');
require_once('telegraph-class.php');

class FileStorage extends Storage
{
    private string $directory = '';                     // директория хранилища
    private array $fileStorage = [];                    // Массив всех объектов класса TelegraphText в хранилище

    /** Инициализирует хранилище, создает директорию по заданному пути, если директории не существует
     * @param string $dir задает путь к директории хранилища
     */
    public function __construct(string $dir = 'storage')
    {
        $this->directory = __DIR__ . '\\' . basename($dir) . '\\';
        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }
    }

    /** Загружает новый объект класса TelegraphText в хранилище в виде файла
     * @param object $object передаваемый объект
     * @return string|false Возвращает путь к файлу, при ошибке возвращает false
     */
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

    /** Считывает объект класса TelegraphText из хранилища
     * @param string $slug путь к объекту
     * @return object|false возвращает объект, либо false в случае ошибки
     */
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

    /** Пересохраняет объект класса TelegraphText по указанному пути
     * @param string $slug путь в перезаписываемому объекту
     * @param object $object исходный объект для проверки
     * @param object $newObject новый объект, который будет записан
     * @return bool возвращает true в случае успешной перезаписи, иначе false
     */
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

    /** Удаляет объект класса TelegraphText из хранилища по указанному пути
     * @param string $slug путь к удаляемому объекту
     * @return bool возвращает true в случае успешного удаления, иначе false
     */
    public function delete(string $slug): bool
    {
        return unlink($this->directory . basename($slug)); 
    }

    /** Выводит все объекты класса TelegraphText из хранилища в виде массива
     * @return array|false возвращает массив, в случае ошибки false
     */
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


//тестирование работоспособности кода пункты 4-9 (раскоментировать для выполнения)
/*
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
*/

//Тестирование модификации класса TelegraphText, пункт 10 (раскоментировать для выполнения)
/*
$a = new TelegraphText('Alex');
$b = new TelegraphText('Dima');
$c = new TelegraphText('Naruto');

$a->editText('Good', 'morning');
$b->editText('hello', 'world');

$pathofA = TelegraphText::$storage->create($a);
$pathofB = $b->storeText();
$a::$storage->create($c);

$c->loadText($pathofA);
$a = TelegraphText::$storage->read($pathofB);

var_dump($a, $c);


$storageArray = TelegraphText::$storage->list();
print_r($storageArray);
*/
