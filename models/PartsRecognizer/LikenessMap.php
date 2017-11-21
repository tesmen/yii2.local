<?php

namespace app\models\PartsRecognizer;

use app\entity\TmPart;

class LikenessMap
{
    private $partType;
    private $partsOfSameType;
    private $partsMetaData;

    public function __construct($partTypeId)
    {
        $this->partType = $partTypeId;
        $this->partsOfSameType = TmPart::findAll(['part_type_id' => $partTypeId]);

        foreach ($this->partsOfSameType as $type) {
            $words = PartNameStripper::stripToArray($type->raw_name);

            $this->partsMetaData[] = [
                'id'    => $type->id,
                'words' => $words,
                'code'  => $type->kod,
            ];
        }
    }

    /**
     * @param array $input
     * @param array $comparable
     * @return int
     */
    private function countLikeness(array $input, array $comparable)
    {
        $likeness = 0;

        foreach ($input as $word) {
            if (in_array($word, $comparable)) {
                $likeness++;
            }
        }

        return $likeness;
    }

    /**
     * @param $str
     * @return array
     */
    public function getMap($str)
    {
        $map = [];
        $input = PartNameStripper::stripToArray($str);

        foreach ($this->partsMetaData as $part) {
            $map[$part['id']] = $this->countLikeness($input, $part['words']);
        }

        return $map;
    }
}
