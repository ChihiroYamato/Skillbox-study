<?php

namespace Modules\Traits;

trait TraitDirectory
{
    private function makeDirectory(string $directory)
    {
        if (!is_dir($directory)) {
            mkdir($directory);
        }
    }
}
