<?php

namespace app\models;

use app\entity\TmPart;
use app\entity\TmPartSynonym;
use app\models\Search\SynonymsSearch;

class TmPartSynonymModel
{
    public static function createSafe($name, $partId)
    {
        if (TmPartSynonym::findOne(['name' => $name])) {
            return false;
        }

        return TmPartSynonym::create($name, $partId);
    }

    public static function getPartSynonyms($id)
    {
        $rec = TmPart::find()->where(['id' => $id]);

        return TmPartSynonym::find()->asArray()->where(['part_id' => $id])->all();
    }

    public static function getPartData($id)
    {
        $rec = TmPart::find()
            ->asArray()
            ->where(['id' => $id])->one();

        $rec['synonyms'] = static::getPartSynonyms($id);


        return $rec;
    }

    public static function search(SynonymsSearch $search)
    {
        $data = [];

        foreach (static::getFoundIds($search) as $foundId) {
            $data[] = static::getPartData($foundId);
        }

        return $data;
    }

    private static function getFoundIds(SynonymsSearch $search)
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
