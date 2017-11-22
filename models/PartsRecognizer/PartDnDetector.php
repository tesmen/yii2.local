<?php

namespace app\models\PartsRecognizer;

class PartDnDetector implements DetectorInterface
{
    // lat cyr cyr
    private static $firstLetters = ['d', 'д'];

    // lat lat cyr
    private static $secondLetters = ['n', 'y', 'у'];


    private static $instance;

    /**
     * @return PartPnDetector
     */
    public static function instance()
    {
        if (empty(static::$instance)) {
            return static::$instance = new static;
        }

        return static::$instance;
    }

    private function generateRegs()
    {
        $output = [];

        foreach (static::$firstLetters as $firstLetter) {
            foreach (static::$secondLetters as $secondLetter) {
                $output[] = "/$firstLetter$secondLetter(\s)?(([0-9]+,?[0-9]*))/i";
            }
        }

        return $output;
    }

    /**
     * @param $str
     * @return bool|int
     */
    public
    function detect($str)
    {
        $prepared = PartNameStripper::stripToString($str);

        foreach ($this->generateRegs() as $regex) {
            if (preg_match($regex, $prepared, $matches)) {
                return intval(str_replace(',', '', $matches[3]));
            };
        }

        return false;
    }
}
