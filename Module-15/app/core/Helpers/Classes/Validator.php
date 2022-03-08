<?php

namespace App\Base\Helpers\Classes;

use App\Base\Exceptions\FormException;

/**
 * Статичный класс для валидации входящих данных
 * используется класс исключений App\Base\Exceptions\FormException
 *
 * @var array [const] TELEGRAPH_PARAMS проверяемые данные из формы Telegraph
 *
 * @method validateForm :array array $postData
 * @method validateEmail :string string $email
 * @method fromTelegraph :array array $postData
 */
class Validator
{
    /** @var array [const] TELEGRAPH_PARAMS проверяемые данные из формы Telegraph */
    protected const TELEGRAPH_PARAMS = ['AUTHOR' => 'Автор', 'TITLE' => 'Заголовок', 'TEXT' => 'Текст'];

    /**
     * Метод проверяет наличие в классе метода соответсвующей валидации и передает в него данные,
     * если в приниемых данных отсутсвует ключ REQUEST_FROM - выбрасывает исключение
     * @param array $postData принимаемые данные для валидации
     * @return array результат валидации
     * @throw FormException
     */
    public static function validateForm(array $postData) : array
    {
        if (isset($postData['REQUEST_FROM']) && method_exists(__CLASS__, 'from' . $postData['REQUEST_FROM'])) {
            return self::{'from' . $postData['REQUEST_FROM']}($postData);
        }
        throw new FormException('Ошибка: Некорректные параметры валидации');
    }

    /**
     * Метод валидирует поле email
     * @param string $email принимаемый email адрес
     * @return string возвращает валидный email, если валидация прошла успешно, иначе выбрасывает исключение
     * @throw FormException
     */
    public static function validateEmail(string $email) : string
    {
        $email = htmlspecialchars($email);
        if (preg_match('/^[\w-]{3,}@[\w]{3,}\.[\w]{2,}$/', $email) != false) {
            return $email;
        }
        throw new FormException('Ошибка: Некорректные поле email');
    }

    /**
     * Метод валидирует данные из формы Telegraph
     * @param array $postData принимаемые данные для валидации
     * @return array возвращает валидные данные, если валидация прошла успешно, иначе выбрасывает исключение
     * @throw FormException
     */
    protected static function fromTelegraph(array $postData = []) : array
    {
        $validData = [];

        foreach (self::TELEGRAPH_PARAMS as $param => $desc) {
            if (empty($postData[$param])) {
                throw new FormException("Ошибка: отсутствует поле $desc");
            }
            $validData[$param] = htmlspecialchars($postData[$param]);
        }

        return $validData;
    }
}
