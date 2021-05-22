<?php

namespace App\Helpers;

class FileStore
{
    public static function Get(string $filename): string|bool
    {
        return file_get_contents('./Data/' . $filename);
    }

    public static function Set(string $filename, string $data): string|bool
    {
        if (!file_exists('./Data')) {
            $result = mkdir('./Data', 0770, true);
            if ($result === false) return false;
        }

        return file_put_contents('./Data/' . $filename, $data);
    }
}
