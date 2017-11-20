<?php

namespace app\models\PartsRecognizer;

use app\entity\TmPartType;

class PartTypeDetector
{
    private $partTypes;

    public function __construct()
    {
        $this->partTypes = TmPartType::find()->asArray()->all();
    }

    public function detectPartType($str)
    {
        $prepared = mb_strtolower(trim($str));

        foreach ($this->partTypes as $partType) {
            $tester = mb_strtolower(trim($partType['name']));

            if (mb_strpos($prepared, $tester) !== false) {
                return intval($partType['id']);
            };
        }
    }
}
