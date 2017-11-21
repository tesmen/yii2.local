<?php

namespace app\models\PartsRecognizer;

use app\util\TextManipulator;

class PartNameStripper
{
    /**
     * @param $str
     * @return array|false|string[]
     */
    public static function stripToArray($str)
    {
        return TextManipulator::createFromString($str)
            ->lowerCase()
            ->removeQuotes()
            ->removeBraces()
            ->getNaturalWords();
    }

    /**
     * @param $str
     * @return string
     */
    public static function stripToString($str)
    {
        return TextManipulator::createFromString($str)
            ->lowerCase()
            ->removeQuotes()
            ->removeBraces();
    }
}
