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
     * @return TmPartSynonym
     */
    public function detect($str)
    {
        return TmPartSynonym::findBySql(
            "SELECT * FROM tm_part_synonyms WHERE trim(LOWER(name)) = trim(LOWER(:str))",
            ['str' => $str]
        )->one();
    }
}
