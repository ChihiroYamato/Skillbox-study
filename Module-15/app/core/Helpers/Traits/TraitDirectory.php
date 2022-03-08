<?php

namespace App\Base\Helpers\Traits;

use App\Base\Exceptions\SimpleException;

/**
 * Трейт для работы с директориями
 *
 * @method  makeDirectory :void string $directory
 */
trait TraitDirectory
{
    /**
     * Создает директорию, если ее не существут
     * @param string $directory путь к директории
     * @throw SimpleException
     */
    final protected static function makeDirectory(string $directory) : void
    {
        if (!is_dir($directory)) {
            if(! mkdir($directory)) {
                throw new SimpleException("Ошибка создания директории $directory");
            }
        }
    }
}
