<?php

namespace app\models\PartsRecognizer;

use app\entity\TmPart;

class PartCodeDetector implements DetectorInterface
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

    /**
     * @param $str
     * @return array|bool|string[]
     */
    public function detect($str)
    {
        $partType = PartTypeDetector::instance()->detect($str);

        if (empty($partType)) {
            return false;
        }

        $part = new TmPart();
        $part->raw_name = $str;
        $part->part_type_id = $partType;
        $part->dn = PartDnDetector::instance()->detect($str);
        $part->pn = PartPnDetector::instance()->detect($str);
        $part->material_id = PartMaterialDetector::instance()->detect($str);

        $map = new LikenessMap($part);

        return $map->getCode($str);
    }
}
