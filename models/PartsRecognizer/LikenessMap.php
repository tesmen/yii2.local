<?php

namespace app\models\PartsRecognizer;

use app\entity\TmPart;

class LikenessMap
{
    private $partType;
    private $partsOfSameType;
    private $partsMetaData = [];

    const THRESHOLD = 50;

    public function __construct(TmPart $part)
    {
        $this->partType = $part->part_type_id;

        $criteria = ['part_type_id' => $part->part_type_id];

        if ($part->material_id) {
            $criteria['material_id'] = $part->material_id;
        }

        if ($part->pn) {
            $criteria['pn'] = $part->pn;
        }

        if ($part->dn) {
            $criteria['dn'] = $part->dn;
        }

        $this->partsOfSameType = TmPart::fromCache($criteria);

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
        $inputWordsCnt = sizeof($input);

        foreach ($this->partsMetaData as $part) {
            $percent = intval($this->countLikeness($input, $part['words']) / $inputWordsCnt * 100);
            $map[$part['code']] = $percent;
        }

        uasort(
            $map,
            function ($a, $b) {
                return $a < $b;
            }
        );

        return $map;
    }

    /**
     * @param $str
     * @return array | bool
     */
    public function getCodes($str)
    {
        $output = [];
        $map = $this->getMap($str);

        if (empty($map)) {
            return false;
        }

        $max = reset($map);
        $confidenceMap = array_count_values($map);

        if ($confidenceMap[$max] > 3) {
            return false;
        }

        foreach ($map as $id => $likeness) {
            if ($likeness < static::THRESHOLD) {
                continue;
            }

            if ($likeness === $max)
                $output[] = $id;
        }

        return $output;
    }
}
