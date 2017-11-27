<?php

namespace app\models\PartsRecognizer;

use app\entity\TmPart;
use app\entity\TmPartSynonym;

class BySynonymCodeDetector implements DetectorInterface
{
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @return static
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
     * @return array|bool|string[]
     */
    public function detect($str)
    {
        $rec = TmPartSynonym::findOne(['name' => PartNameStripper::stripToString($str)]);

        return $rec
            ? $rec->name
            : false;
    }
}
