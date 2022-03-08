<?php

namespace App\Base\Helpers\Traits;

use Exception;

/**
 * Трейт для обработки базовых (отловленных) исключений проекта
 *
 * @var array $emergencyMail Массив заголовков для отправки экстренного сообщения об ошибке
 *
 * @method sendEmergencyMail :bool Exception $error, string $className, ?Exception $previous
 */
trait SimpleExceptionHandler
{
    /**
     * @var array $emergencyMail Массив заголовков для отправки экстренного сообщения об ошибке
     */
    protected static array $emergencyMail = [
        'to' => 'test@example.ru',
        'subject' => 'Error',
        'headers' => [
            'From' => 'test@example.ru',
            'Reply-To' => 'test@example.ru',
            'X-Mailer' => 'php',
        ],
    ];

    /**
     * Метод отправляет экстренное сообщение об ошибке по заданным заголовкам
     * @param Exception $error экземпляр выброшенной ошибки
     * @return bool возвращает результат отправки письма
     */
    final protected static function sendEmergencyMail(Exception $error) : bool
    {
        $massage = 'ERROR:' . $error->getMessage() . ' IN FILE:' . $error->getFile() . 'ON LINE: ' . $error->getLine();
        $massage = wordwrap($massage, 65);

        return mail(self::$emergencyMail['to'], self::$emergencyMail['subject'], $massage, self::$emergencyMail['headers']);
    }

    /**
     * Метод прерывает выполнение скрипта по переданному исключению выполняя пользовательские директивы
     * @param Exception $error экземпляр выброшенной ошибки
     * @param string $className имя класса в котором выброшено исключение
     * @param ?Exception $previous [optional] предыдущее выброшенное исключение
     */
    final protected static function getFatalError(Exception $error, string $className, ?Exception $previous = null) : void
    {
        if (! self::sendEmergencyMail($error)) {
            echo 'Error: ошибка отравки отчета об ошибке<br>';
        }
        if ($previous !== null) {
            echo 'Error: исключение с сообщением - ' . $previous->getMessage() . '<br>';
            echo 'Возникло и не было обработано в классе ' . $className . '<br>';
        } else {
            echo 'Error: ошибка в классе '. $className . '<br>';
        }
        echo 'Сообщение ошибки: ' . $error->getMessage() . '<br>';
        die;
    }
}
