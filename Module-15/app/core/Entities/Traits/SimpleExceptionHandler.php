<?php

namespace App\Base\Entities\Traits;

use Exception;

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
}
