<?php

namespace app\models;

use Convertio\Convertio;

class FileConverter
{
    public static function convertXslxToCsv($input, $output)
    {
        $API = new Convertio("df7177eac48cae6559c9990e46417c8d");

        return $API
            ->start($input, 'csv')
            ->wait()// Wait for conversion finish
            ->download($output)
            ->delete();
    }
}
