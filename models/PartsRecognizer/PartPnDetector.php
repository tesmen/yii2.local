<?php

namespace app\models\PartsRecognizer;

class PartPnDetector
{
    private static $regs = [
        '/pn(\s)?(([0-9]+,?[0-9]*))/i' => 3, //lat
        '/пу(\s)?(([0-9]+,?[0-9]*))/i' => 3, //cyr п cyr у
        '/пy(\s)?(([0-9]+,?[0-9]*))/i' => 3, //cyr п lat y
        '/py(\s)?(([0-9]+,?[0-9]*))/i' => 3, //lat p lat y
    ];

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

    /**
     * @param $str
     * @return bool|int
     */
    public function detect($str)
    {
        $prepared = PartNameStripper::stripToString($str);

        foreach (static::$regs as $regex => $return) {
            if (preg_match($regex, $prepared, $matches)) {
                return intval(str_replace(',', '', $matches[$return]));
            };
        }

        return false;
    }
}
