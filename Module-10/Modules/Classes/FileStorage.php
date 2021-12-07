<?php
namespace Modules\Classes;

use Modules\Abstracts\Storage,
    Modules\Traits\TraitDirectory;

class FileStorage extends Storage
{
    use TraitDirectory;

    private string $directory = '';                                 // директория хранилища
    private array $fileStorage = [];                                // Массив всех объектов класса TelegraphText
    private array $eventFlags = [];                                 //
    private ?\Closure $eventCallback = null;                        //

    private static string $logsPath = '';                           // Путь к файлу логов

    /**
     * Инициализирует хранилище, создает директорию по заданному пути, если директории не существует
     * @param string $dir задает путь к директории хранилища
     */
    public function __construct(string $dir = 'storage')
    {
        $this->directory = dirname(__DIR__, 2) . '\\' . basename($dir) . '\\';
        self::$logsPath = dirname(__DIR__, 2) . '\logs\file-storage-log';
        $this->makeDirectory($this->directory);
        $this->makeDirectory(dirname(self::$logsPath));

        $methodList = get_class_methods($this);
        foreach ($methodList as $method) {
            $this->eventFlags[$method] = false;
        }
    }

    public function __call(string $name, array $arguments) : mixed
    {
        if (method_exists($this, $name)) {
            if ($this->eventFlags[$name]) {
                ($this->eventCallback)();
            }
            foreach ($arguments as $param => $value) {
                $paramToString[] = '$arguments[' . $param . ']';
            }
            $paramToString = implode(', ', $paramToString);
            return eval('return $this->' . $name . '(' . $paramToString . ');');
        }
    }

    /**
     * Загружает новый объект класса TelegraphText в хранилище в виде файла
     * @param object $object передаваемый объект
     * @return string|false Возвращает путь к файлу, при ошибке возвращает false
     */
    protected function create(object $object): string|false
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

    /**
     * Считывает объект класса TelegraphText из хранилища
     * @param string $slug путь к объекту
     * @return object|false возвращает объект, либо false в случае ошибки
     */
    protected function read(string $slug): object|false
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

    /**
     * Пересохраняет объект класса TelegraphText по указанному пути
     * @param string $slug путь в перезаписываемому объекту
     * @param object $object исходный объект класса TelegraphText для проверки
     * @param object $newObject новый объект класса TelegraphText, который будет записан
     * @return bool возвращает true в случае успешной перезаписи, иначе false
     */
    protected function update(string $slug, object $object, object $newObject): bool
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

    /**
     * Удаляет объект класса TelegraphText из хранилища по указанному пути
     * @param string $slug путь к удаляемому объекту
     * @return bool возвращает true в случае успешного удаления, иначе false
     */
    protected function delete(string $slug): bool
    {
        return unlink($this->directory . basename($slug));
    }

    /**
     * Выводит все объекты класса TelegraphText из хранилища в виде массива
     * @return array|false возвращает массив, в случае ошибки false
     */
    protected function list(): array|false
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

    // Методы интерфейсов

    public function logMessage(string $error) : bool
    {
        if (false === file_put_contents(self::$logsPath, serialize($error), FILE_APPEND)) {
            return false;
        }
        return true;
    }

    public function lastMessages(int $countErrors = 0): array|false
    {
        if (false === ($massages = file_get_contents(self::$logsPath))) {
            return false;
        }
        $massages = array_slice(array_filter(explode(';', $massages)), -$countErrors);
        foreach ($massages as &$stringError) {
            $stringError = unserialize($stringError . ';');
        }
        return $massages;
    }

    public function attachEvent(?string $method = null, ?callable $callbackFun = null) : bool
    {
        if (isset($this->eventFlags[$method]) && is_callable($callbackFun)) {
            $this->eventFlags[$method] = true;
            $this->eventCallback = $callbackFun;
            return true;
        }
        return false;
    }

    public function detouchEvent(?string $method = null) : bool
    {
        if (isset($this->eventFlags[$method])) {
            $this->eventFlags[$method] = false;
            return true;
        }
        return false;
    }
}