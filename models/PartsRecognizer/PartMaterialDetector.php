<?php

namespace app\models\PartsRecognizer;

class PartMaterialDetector
{
    private static $materials = [
        1 => ['латун'],
        2 => ['медь', 'медны'],
        3 => ['алюмини', 'алюмин', 'алюм.'],
        4 => ['бронз'],
        5 => ['резин'],
        6 => ['сталь'],
        7 => ['дер.', 'дерев'],
        8 => ['чугун.'],
    ];

    private static $instance;

    /**
     * @return PartMaterialDetector
     */
    public static function instance()
    {
        if (empty(static::$instance)) {
            return static::$instance = new static;
        }

        return static::$instance;
    }

    public function detect($str)
    {
        $prepared = PartNameStripper::stripToString($str);

        foreach (static::$materials as $id => $synonyms) {
            foreach ($synonyms as $name) {
                if ($position = mb_strpos($prepared, $name)) {
                    return $id;
                };
            }
        }

        return false;
    }
}
