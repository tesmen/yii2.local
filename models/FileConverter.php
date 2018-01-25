<?php

namespace app\models;

use Convertio\Convertio;

class FileConverter
{
    public static function convertXslxToCsv($input, $output)
    {
        $API = new Convertio("462533cbdfd192ed98c5ef7bdf08fd24");
//        $API = new Convertio("9227fb0246791187fa67d939aebc9245");

        return $API
            ->start($input, 'csv')
            ->wait()// Wait for conversion finish
            ->download($output)
            ->delete();
    }
}
