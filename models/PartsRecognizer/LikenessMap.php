<?php

namespace app\models\PartsRecognizer;

use app\entity\TmPart;

class LikenessMap
{
    private $partType;
    private $map;
    private $partsOdSameType;

    public function __construct($partTypeId)
    {
        $this->partType = $partTypeId;
        $this->partsOdSameType = TmPart::findAll(['part_type_id' => $partTypeId]);
    }
}
