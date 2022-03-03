<?php

namespace App\Base\Entities\Traits;

trait TraitDirectory
{
    protected function makeDirectory(string $directory)
    {
        if (!is_dir($directory)) {
            mkdir($directory);
        }
    }
}
