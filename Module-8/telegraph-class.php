<?php

class TelegraphText
{
    private string $title = '';                                         // Заголовок
    private string $text = '';                                          // Текст
    private string $author = '';                                        // Автор
    private string $published = '';                                     // Дата публикации
    private string $slug = '';                                          // Уникальное имя объекта

    private const DIRECTORY =  __DIR__ . '\publishing\\';               // Стандартная директория для записи


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
    }

    /**
     * Записывает данные публикации в отдельный файл с названием $slug
     * @return string|false Возвращает поле $slug при успешном выполнении, false при ошибке
     */
    public function storeText() : string|false
    {
        $storeTextArray = [
            'title' => $this->title,
            'text' => $this->text,
            'author' => $this->author,
            'published' => $this->published,
        ];
        if (false === file_put_contents(self::DIRECTORY . $this->slug, serialize($storeTextArray))) {
            return false;
        }
        return $this->slug;
    }

    /**
     * Записывает в объект данные публикации из файла с названием $slug
     * @param string $slug принимаемый параметр названия файла, из которого произодится запись
     * @return string|false при успешном выполнении метода возвращает поле $text, иначе ыозвращает false
     */
    public function loadText(string $slug) : string|false
    {
        if (!($loadTextArray = file_get_contents(self::DIRECTORY . $slug))) {
            return false;
        }
        $loadTextArray = unserialize($loadTextArray);
        if (!isset($loadTextArray['title'], $loadTextArray['text'], $loadTextArray['author'], $loadTextArray['published'])) {
            return false;
        }

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

    public function changeSlug(string $newSlug)
    {
        $this->slug = $newSlug;
    }

    public function getSlug() : string
    {
        return $this->slug;
    }
}
