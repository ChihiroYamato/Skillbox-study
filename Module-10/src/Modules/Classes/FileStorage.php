<?php
namespace Base\Modules\Classes;

use Base\Modules\Abstracts\Storage,
    Base\Modules\Traits\TraitDirectory;

final class FileStorage extends Storage
{
    use TraitDirectory;

    protected string $directory = '';                                 // директория хранилища
    protected array $fileStorage = [];                                // Массив всех объектов класса TelegraphText
    protected array $eventFlags = [];                                 // Массив с флагами прослушки методов
    protected ?\Closure $eventCallback = null;                        // callback функция при прослушивании

    protected static string $logsPath = '';                           // Путь к файлу логов

    /**
     * Инициализирует хранилище, создает директорию по заданному пути, если директории не существует
     * @param string $dir задает путь к директории хранилища
     */
    public function __construct(string $dir = 'storage')
    {
        // Инициализация директорий для хранения текстов и логов
        $this->directory = dirname(__DIR__, 3) . '/' . basename($dir) . '/';
        self::$logsPath = dirname(__DIR__, 3) . '/logs/file-storage-log';
        $this->makeDirectory($this->directory);
        $this->makeDirectory(dirname(self::$logsPath));

        // Инициализация флагов
        $methodList = get_class_methods($this);
        foreach ($methodList as $method) {
            $this->eventFlags[$method] = false;
        }
    }

    /**
     * Загружает новый объект класса TelegraphText в хранилище в виде файла
     * @param object $object передаваемый объект
     * @return string|false Возвращает путь к файлу, при ошибке возвращает false
     */
    public function create(object $object): string|false
    {
        if ($this->eventFlags[__FUNCTION__]) {
            ($this->eventCallback)();
        }
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
    public function read(string $slug): object|false
    {
        if ($this->eventFlags[__FUNCTION__]) {
            ($this->eventCallback)();
        }
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
    public function update(string $slug, object $object, object $newObject): bool
    {
        if ($this->eventFlags[__FUNCTION__]) {
            ($this->eventCallback)();
        }
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
    public function delete(string $slug): bool
    {
        if ($this->eventFlags[__FUNCTION__]) {
            ($this->eventCallback)();
        }
        return unlink($this->directory . basename($slug));
    }

    /**
     * Выводит все объекты класса TelegraphText из хранилища в виде массива
     * @return array|false возвращает массив, в случае ошибки false
     */
    public function list(): array|false
    {
        if ($this->eventFlags[__FUNCTION__]) {
            ($this->eventCallback)();
        }
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

    /**
     * Записывает error в в файл логов
     * @param string $error Строка ошибки
     * @return bool Возвращает true в случае успешной записи ошибки в файл, иначе false
     */
    public function logMessage(string $error) : bool
    {
        if (false === file_put_contents(self::$logsPath, serialize($error), FILE_APPEND)) {
            return false;
        }
        return true;
    }

    /**
     * Возвращает countErrors ошибок из файла логов
     * @param int $countErrors число записей, которые необходимо вернуть
     * @return array|false возвращает массив записей в случае успешного извлечения, иначе false
     */
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

    /**
     * Присваивает указанному методу флаг прослушки, при последующих вызовах указанного метода
     * будет выполнена callback функция
     * @param ?string $method Существующий метод класса FileStorage
     * @param ?callable $callbackFun callback функция, которую необходимо выполнять при вызове метода
     * @return bool возвращает true в случае успешной установки флага, иначе false
     */
    public function attachEvent(?string $method = null, ?callable $callbackFun = null) : bool
    {
        if (isset($this->eventFlags[$method]) && is_callable($callbackFun)) {
            $this->eventFlags[$method] = true;
            $this->eventCallback = $callbackFun;
            return true;
        }
        return false;
    }

    /**
     * Деактивирует флаг прослушки для метода, установленный в методе attachEvent
     * @param ?string $method Существующий метод класса FileStorage, который необходимо прекратить прослушивать
     * @return bool возвращает trueв с случае успешной деактивации, иначе false
     */
    public function detouchEvent(?string $method = null) : bool
    {
        if (isset($this->eventFlags[$method])) {
            $this->eventFlags[$method] = false;
            return true;
        }
        return false;
    }
}
