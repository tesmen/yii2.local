<?php

namespace app\models;

use app\entity\TmPartSynonym;
use app\models\Search\SynonymsSearch;

class TmPartSynonymModel
{
    public static function createSafe($name, $code)
    {
        if (TmPartSynonym::findOne(['name' => $name])) {
            return false;
        }

        return TmPartSynonym::create($name, $code);
    }

    public static function search(SynonymsSearch $search)
    {
        $criteria = [];

        if (!empty($search->code)) {
            $criteria['tp.code'] = $search->code;
        }

        if (!empty($search->ved_name)) {
            $criteria['tp.code'] = $search->code;
        }

        if (!empty($search->code)) {
            $criteria['tp.code'] = $search->code;
        }

        $q = (new \yii\db\Query())
            ->select(['DISTINCT(tp.id)'])
            ->from('tm_parts tp')
            ->join('JOIN', 'tm_part_synonyms')
            ->where($criteria)
            ->offset($search->getOffset())
            ->limit($search->limit);

        $data = $q->column();

        return $data;
    }
}
