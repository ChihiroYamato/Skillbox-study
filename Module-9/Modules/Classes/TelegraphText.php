<?php

namespace Modules\Classes;

class TelegraphText
{
    private string $title = '';                                         // Заголовок
    private string $text = '';                                          // Текст
    private string $author = '';                                        // Автор
    private string $published = '';                                     // Дата публикации
    private string $slug = '';                                          // Уникальное имя объекта

    public static object $storage;                                      // объект класса FileStorage


    /**
     * Инициализирует поля $author $published $slug при создании объекта
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
        if (!isset(self::$storage)) {
            self::$storage = new FileStorage();
        }
    }

    /**
     * Записывает данные публикации в отдельный файл с модифицированным названием $slug
     * @return string|false Возвращает модифицированное поле $slug при успешном выполнении, false при ошибке
     */
    public function storeText() : string|false
    {
        return self::$storage->create($this);
    }

    /**
     * Записывает в объект данные публикации из файла с названием $slug
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
     * если не передан, или передан null - запись (перезапись) не производится
     * @param string|null $title принимаемое значение для записи заголовка.
     * если не передан, или передан null - запись (перезапись) не производится
     */
    public function editText (?string $text = null, ?string $title = null)
    {
        $this->text = trim($text) ?? $this->text;
        $this->title = trim($title) ?? $this->title;
    }

    /**
     * Метод перезаписывает поле $slug
     * @param string $newSlug принимаемый параметр, перезаписывается в $this->slug
     */
    public function changeSlug(string $newSlug)
    {
        $this->slug = $newSlug;
    }

    /**
     * Метод возвращает поле $slug
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * Метод возвращает поля класса в виде массива
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
