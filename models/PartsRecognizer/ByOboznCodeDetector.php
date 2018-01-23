<?php

namespace app\models\PartsRecognizer;

use app\entity\TmPart;
use app\entity\TmPartSynonym;
use yii\db\ActiveRecord;

class ByOboznCodeDetector implements DetectorInterface
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
     * @param $obozn
     * @return TmPart | ActiveRecord
     */
    public function detect($obozn)
    {
        $dot = TmPart::findBySql(
            "SELECT * FROM tm_parts WHERE trim(LOWER(obez)) = trim(LOWER(:str))",
            ['str' => $obozn]
        )->one();

        if ($dot) {
            return $dot;
        }

        $dottlessString = str_replace('.', '', $obozn);

        return TmPart::findBySql(
            "SELECT * FROM tm_parts WHERE trim(LOWER(obez)) = trim(LOWER(:str))",
            ['str' => $dottlessString]
        )->one();
    }
}
