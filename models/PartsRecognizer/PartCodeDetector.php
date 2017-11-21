<?php

namespace app\models\PartsRecognizer;

class PartCodeDetector
{
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @return PartCodeDetector
     */
    public static function instance()
    {
        if (empty(static::$instance)) {
            return static::$instance = new static;
        }

        return static::$instance;
    }

    public function detectPartCode($str)
    {
        $partType = PartTypeDetector::instance()->detectPartType($str);
        if (empty($partType)) {
            return false;
        }

        $map = new LikenessMap($partType)->;

        return;
    }
}
