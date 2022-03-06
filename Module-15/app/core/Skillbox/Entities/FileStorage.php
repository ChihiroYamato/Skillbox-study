<?php

namespace App\Base\Skillbox\Entities;

use App\Base\Helpers\Traits\SimpleExceptionHandler;
use App\Base\Helpers\Traits\TraitDirectory;
use App\Base\Exceptions\SimpleException;

/**
 * Класс хранилище для объектов класса TelegraphText
 *
 * @var string $directory директория хранилища
 * @var array $fileStorage Массив всех объектов класса TelegraphText
 * @var array $eventFlags Массив с флагами прослушки методов
 * @var ?\Closure $eventCallback callback функция при прослушивании
 * @var string $logsPath [static] Путь к файлу логов
 *
 * @method __construct :void string $dir
 * @method create :string|false object $object
 * @method read :object|false string $slug
 * @method update :bool string $slug, object $object, object $newObject
 * @method delete :bool string $slug
 * @method list :array|false
 * @method logMessage :bool string $error
 * @method lastMessages :array|false int $countErrors
 * @method attachEvent :bool ?string $method, ?callable $callbackFun
 * @method detouchEvent :bool ?string $method
 */
final class FileStorage extends Storage
{
    use TraitDirectory, SimpleExceptionHandler;

    /** @var string $directory директория хранилища */
    protected string $directory;

    /** @var array $fileStorage Массив всех объектов класса TelegraphText */
    protected array $fileStorage;

    /** @var array $eventFlags Массив с флагами прослушки методов */
    protected array $eventFlags;

    /** @var ?\Closure $eventCallback callback функция при прослушивании */
    protected ?\Closure $eventCallback = null;

    /** @var string $logsPath [static] Путь к файлу логов */
    protected static string $logsPath;

    /**
     * Метод инициализирует хранилище, создает директорию по заданному пути, если директории не существует
     * @param string $dir [optional] директория хранилища
     */
    public function __construct(string $dir = STORAGE_DIR)
    {
        // Инициализация директорий для хранения текстов и логов
        $this->directory = STORAGE_BASE_PATH . trim($dir, '\\/') . '/';
        self::$logsPath = LOGS_PATH . STORAGE_LOGS_NAME;

        try {
            // Создание директорий для текстов и логов
            self::makeDirectory($this->directory);
            self::makeDirectory(dirname(self::$logsPath));
        } catch (SimpleException $error) {
            self::sendEmergencyMail($error);
        }

        // Инициализация флагов
        $methodList = get_class_methods($this);
        foreach ($methodList as $method) {
            $this->eventFlags[$method] = false;
        }
    }

    /**
     * Метод загружает новый объект класса TelegraphText в хранилище в виде файла
     * @param object $object передаваемый объект
     * @return string|false Возвращает путь к файлу, при ошибке возвращает false
     */
    public function create(object $object) : string|false
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
     * Метод считывает объект класса TelegraphText из хранилища
     * @param string $slug путь к объекту
     * @return object|false возвращает объект, либо false в случае ошибки
     */
    public function read(string $slug) : object|false
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
     * Метод пересохраняет объект класса TelegraphText по указанному пути
     * @param string $slug путь в перезаписываемому объекту
     * @param object $object исходный объект класса TelegraphText для проверки
     * @param object $newObject новый объект класса TelegraphText, который будет записан
     * @return bool возвращает true в случае успешной перезаписи, иначе false
     */
    public function update(string $slug, object $object, object $newObject) : bool
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
     * Метод удаляет объект класса TelegraphText из хранилища по указанному пути
     * @param string $slug путь к удаляемому объекту
     * @return bool возвращает true в случае успешного удаления, иначе false
     */
    public function delete(string $slug) : bool
    {
        if ($this->eventFlags[__FUNCTION__]) {
            ($this->eventCallback)();
        }
        return unlink($this->directory . basename($slug));
    }

    /**
     * Метод выводит все объекты класса TelegraphText из хранилища в виде массива
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
     * Метод записывает error в в файл логов
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
     * Метод возвращает countErrors ошибок из файла логов
     * @param int $countErrors число записей, которые необходимо вернуть
     * @return array|false возвращает массив записей в случае успешного извлечения, иначе false
     */
    public function lastMessages(int $countErrors = 0) : array|false
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
     * Метод присваивает указанному методу флаг прослушки, при последующих вызовах указанного метода
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
     * Метод деактивирует флаг прослушки для метода, установленный в методе attachEvent
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
