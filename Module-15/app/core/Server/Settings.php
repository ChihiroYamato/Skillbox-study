<?php

namespace App\Base\Server;

/**
 * Класс глобальных настроек проекта
 *
 * @var array [const] SETTINGS массив глобальных настроек проекта
 *
 * @method getMailSettings :array
 * @method getBDSettings :array
 */
final class Settings
{
    /** @var array [const] SETTINGS массив глобальных настроек проекта */
    private const SETTINGS = [
        'MAIL' => [
            'SERVER' => [
                'HOST' => 'smtp.gmail.com',
                'USER_NAME' => 'testing@gmail.com',
                'PASSWORD' => '**************',
            ],
            'SEND' => [
                'ADMIN_MAIL' => 'testing@gmail.com',
                'FROM_ADDRESS' => 'testing@gmail.com',
                'FROM_NAME' => 'Admin',
            ],

        ],
        'DATABASE' => [],
    ];

    /**
     * Метод возвращает настроки mail в виде массива
     * @return array
     */
    final public static function getMailSettings() : array
    {
        return self::SETTINGS['MAIL'];
    }

    /**
     * Метод возвращает настроки базы данных в виде массива
     * @return array
     */
    final public static function getBDSettings() : array
    {
        return self::SETTINGS['DATABASE'];
    }
}
