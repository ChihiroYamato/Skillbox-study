<?php

namespace App\Base\Helpers\Classes;

use DOMDocument;

/**
 * Класс сапортер для создания html сущностей, используя DOMDocument
 *
 * @var array HTML_CLASS_KEYS [const] массив css классов для html вывода
 * @var ?DOMDocument $htmlSupport экземпляр класса DOMDocument
 *
 * @method __construct :void
 * @method returnDiv :string string $message, string $class, bool $usePredefined, bool $boldText
 */
class HtmlSupport
{
    /** @var array HTML_CLASS_KEYS [const] массив c предопределенными css классами для html вывода */
    protected const HTML_CLASS_KEYS = [
        'ALERT' => 'alert alert-danger padding-around',
        'SUCCESS' => 'alert alert-success padding-around',
        'WARNING' => '',
        'DEFAULT' => 'alert alert-secondary padding-around',
    ];

    /** @var ?DOMDocument $htmlSupport экземпляр класса DOMDocument */
    protected static ?DOMDocument $htmlSupport = null;

    /**
     * Метод инициирует статичное свойство $htmlSupport при создании объекта
     */
    public function __construct()
    {
        if (self::$htmlSupport === null) {
            self::$htmlSupport = new DOMDocument();
        }
    }

    /**
     * Метод возвращает переданное сообщение, обернутое в div элемент с указанными css классами
     * @param string $message передаемое сообщение
     * @param string $class ключ предопределенных css классов
     * @param bool $usePredefined [optional] если передан false - устанавливает содержимое $class в атрибут class по умолчанию true
     * @param bool $boldText [optional] если установлен true - выделяет сообщение жирным шрифтом, по умолчанию false
     * @return string сообщение, отформатированное для вывода в HTML, в случае ошибки - пустая строка
     */
    public function returnDiv(string $message, string $class, bool $usePredefined = true, bool $boldText = false) : string
    {
        $newDiv = self::$htmlSupport->createElement('div');

        $divClass = self::$htmlSupport->createAttribute('class');
        $divClass->value = ($usePredefined) ? (self::HTML_CLASS_KEYS[$class] ?? self::HTML_CLASS_KEYS['DEFAULT']) : $class;

        $newDiv->appendChild($divClass);
        $newDiv->appendChild(self::$htmlSupport->createElement(($boldText) ? 'b' : 'span', $message));

        $result = self::$htmlSupport->saveHTML($newDiv);

        return ($result !== false) ? $result : '';
    }
}
