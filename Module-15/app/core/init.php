<?php

/** Старт сессии */
session_start();

/** Подключение автозагрузчика от Composer */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/** Установка пользовательского обработчика ошибок */
set_exception_handler('App\Base\Helpers\Classes\Handler::handleNonCatchException');
