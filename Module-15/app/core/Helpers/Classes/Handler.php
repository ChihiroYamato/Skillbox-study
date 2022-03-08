<?php

namespace App\Base\Helpers\Classes;

use App\Base\Helpers\Classes\HtmlSupport;
use Throwable;

/**
 * Класс для обработки пользовательских событий
 *
 * @method handleNonCatchException :void Throwable $exception
 */
class Handler
{
    /**
     * Метод-обработчик неотловленного исключения - выводит сообщение об исключении
     * @param Throwable $exception экземляр исключения
     */
    public static function handleNonCatchException(Throwable $exception) : void
    {
        $message = (new HtmlSupport())->returnDiv($exception->getMessage(), 'ALERT');

        require_once PROJECT_LOCAL_PATH . '/app/templates/header.php';
        echo $message;
        require_once PROJECT_LOCAL_PATH . '/app/templates/footer.php';
    }
}
