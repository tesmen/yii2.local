<?php

namespace app\models\PartsRecognizer;

use app\entity\TmPartType;

class PartTypeDetector
{
    private $partTypes;
    private static $instance;

    private function __construct()
    {
        $this->partTypes = TmPartType::find()->asArray()->all();
    }

    /**
     * @return PartTypeDetector
     */
    public static function instance()
    {
        if (empty(static::$instance)) {
            return static::$instance = new static;
        }

        return static::$instance;
    }

    public function detectPartType($str)
    {
        $prepared = mb_strtolower(trim($str));
        $variants = [];

        foreach ($this->partTypes as $partType) {

            $tester = mb_strtolower(trim($partType['name']));
            $position = mb_strpos($prepared, $tester);

            if ($position !== false) {
                $variants[$position] = $partType['id'];
            };
        }

        ksort($variants);

        if (empty($variants)) {
            return null;
        }

        return intval(reset($variants));

    }
}
