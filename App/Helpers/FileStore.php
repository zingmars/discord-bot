<?php

namespace App\Helpers;

class FileStore
{
    public static function Get(string $filename): string
    {
        return file_get_contents('./Data/' . $filename);
    }

    public static function Set(string $filename, string $data): string
    {
        mkdir('./Data', 0770, true);
        return file_put_contents('./Data/' . $filename, $data);
    }
}
