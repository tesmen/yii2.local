<?php

namespace app\services;

class FileService
{
    public static function getBatchDir($file = null)
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'batch';

        if ($file) {
            $dir .= DIRECTORY_SEPARATOR . $file;
        }

        return $dir;
    }

    public static function getUploadDir($file = null)
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'upload';

        if ($file) {
            $dir .= DIRECTORY_SEPARATOR . $file;
        }

        return $dir;
    }

    public static function getOutputDir($file = null)
    {
        $dir = static::getBatchDir() . DIRECTORY_SEPARATOR . 'output';

        if ($file) {
            $dir .= DIRECTORY_SEPARATOR . $file;
        }

        return $dir;
    }
}
