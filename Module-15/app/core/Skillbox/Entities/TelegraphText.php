<?php

namespace App\Base\Skillbox\Entities;

use Exception;

/**
 * Класс создания и хранения текстов
 *
 * @var string $title Заголовок
 * @var string $text Текст
 * @var string $author Автор
 * @var string $published Дата публикации
 * @var string $slug Уникальное имя объекта
 * @var FileStorage $storage [static] объект класса FileStorage
 *
 * @method __construct :void string $author
 * @method __set :void string $name, mixed $value
 * @method __get :mixed string $name
 * @method storeText :string|false
 * @method loadText :string|false string $slug
 * @method editText :TelegraphText ?string $text, ?string $title
 * @method changeSlug :void string $newSlug
 * @method getSlug :string
 * @method getAllField :array
 */
final class TelegraphText
{
    /** @var string $title Заголовок */
    private string $title;

    /** @var string $text Текст */
    private string $text;

    /** @var string $author Автор */
    private string $author;

    /** @var string $published Дата публикации */
    private string $published;

    /** @var string $slug Уникальное имя объекта */
    private string $slug;

    /** @var FileStorage $storage [static] объект класса FileStorage */
    protected static ?FileStorage $storage = null;


    /**
     * Метод инициализирует поля $author $published $slug при создании объекта
     * @param string $author принимаемый параметр.
     * если не задан, инициализация всех перечисленных полей не производится.
     */
    public function __construct(string $author = '__default__')
    {
        if ($author !== '__default__') {
            $this->author = $author;
            $this->published = date('H:i:s j-M-y (l)');
            $this->slug = mb_strtolower(substr($author, 0, 3) . '-' . date('j-M-y'));
        }
        if (self::$storage === null) {
            self::$storage = new FileStorage();
        }
    }

    /**
     * Метод устанавливает значения свойствам класса
     * @param string $name имя свойства
     * @param mixed $value значение свойства
     */
    public function __set(string $name, mixed $value) : void
    {
        match ($name) {
            'author'    => $this->author = mb_substr($value, 0, 120),
            'slug'      => $this->slug = preg_replace('~[^a-z1-9.\-_]~i', '', $value),
            'published' => $this->published = ((int) $value > time()) ? date('H:i:s j-M-y (l)', (int) $value) : date('H:i:s j-M-y (l)'),
            'text'      => $this->storeText(),
            default     => null,
        };
    }

    /**
     * Метод возвращает значение свойства класса
     * @param string $name имя свойства
     */
    public function __get(string $name) : mixed
    {
        return match ($name) {
            'author'    => $this->author,
            'slug'      => $this->slug,
            'published' => $this->published,
            'text'      => $this->loadText($this->slug),
            default     => null,
        };
    }

    /**
     * Метод записывает данные публикации в отдельный файл с модифицированным названием $slug
     * @return string|false Возвращает модифицированное поле $slug при успешном выполнении, false при ошибке
     */
    public function storeText() : string|false
    {
        return self::$storage->create($this);
    }

    /**
     * Метод записывает в объект данные публикации из файла с названием $slug
     * @param string $slug принимаемый параметр названия файла, из которого произодится запись
     * @return string|false при успешном выполнении метода возвращает поле $text, иначе ыозвращает false
     */
    public function loadText(string $slug) : string|false
    {
        if (false === ($newObject = self::$storage->read($slug))) {
            return false;
        }
        $loadTextArray = $newObject->getAllField();

        $this->title = $loadTextArray['title'];
        $this->text = $loadTextArray['text'];
        $this->author = $loadTextArray['author'];
        $this->published = $loadTextArray['published'];
        $this->slug = $slug;

        return $this->text;
    }

    /**
     * Метод позволяет записать (перезаписать) поля $text $title
     * @param string|null $text принимаемое значение для записи текста.
     * если не передан, или передан null - запись (перезапись) не производится,
     * если передан и длина меньше 1 или больше 500 - выбрасывает исключение
     * @param string|null $title принимаемое значение для записи заголовка.
     * если не передан, или передан null - запись (перезапись) не производится
     * @return TelegraphText возвращает текущий экземпляр класса
     * @throw Exception
     */
    public function editText(?string $text = null, ?string $title = null) : TelegraphText
    {
        if ($text !== null) {
            $this->text = trim($text);
            $strlen = mb_strlen($this->text);
            if ($strlen < 1 || $strlen > 500) {
                throw new Exception('Некорректная длина текста, допустимая длина от 1 до 500 символов');
            }
        }
        if ($title !== null) {
            $this->title = trim($title);
        }

        return $this;
    }

    /**
     * Метод перезаписывает поле $slug
     * @param string $newSlug принимаемый параметр, перезаписывается в $this->slug
     */
    public function changeSlug(string $newSlug) : void
    {
        $this->slug = $newSlug;
    }

    /**
     * Метод возвращает поле $slug
     * @return string поле $slug
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * Метод возвращает поля класса в виде массива
     * @return array поля класса в виде массива
     */
    public function getAllField() : array
    {
        return [
            'title' => $this->title,
            'text' => $this->text,
            'author' => $this->author,
            'published' => $this->published,
        ];
    }
}
