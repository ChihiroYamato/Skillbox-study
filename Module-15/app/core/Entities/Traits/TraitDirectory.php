<?php

namespace App\Base\Entities\Traits;

use App\Base\Exceptions\SimpleException;

trait TraitDirectory
{
    /**
     * Создает директорию, если ее не существут
     * @param string $directory путь к директории
     * @throw SimpleException
     */
    protected static function makeDirectory(string $directory)
    {
        if (!is_dir($directory)) {
            if(! mkdir($directory)) {
                throw new SimpleException("Ошибка создания директории $directory");
            }
        }
    }
}
