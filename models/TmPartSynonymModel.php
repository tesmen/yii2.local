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

    public static function update($name, $synonymId)
    {
        $rec = TmPartSynonym::findById($synonymId);

        if (!$rec) {
            return false;
        }
        $rec->name = $name;

        return $rec->save();
    }

    public static function getPartSynonyms($id)
    {
        $rec = TmPart::find()->where(['id' => $id]);

        return TmPartSynonym::find()->asArray()->where(['part_id' => $id])->all();
    }

    public static function getPartData($id)
    {
        $data = TmPart::find()
            ->asArray()
            ->where(['id' => $id])->one();

        if (empty($data)) {
            return false;
        }

        $data['synonyms'] = static::getPartSynonyms($id);

        return $data;
    }

    public static function search(SynonymsSearch $search)
    {
        $data = [];

        foreach (static::getIds($search) as $foundId) {
            $data[] = static::getPartData($foundId);
        }

        return $data;
    }

    public static function getCount(SynonymsSearch $search)
    {
        return static::getQuery($search)->count('DISTINCT(tp.id)');
    }

    private static function getIds(SynonymsSearch $search)
    {
        return static::getQuery($search)->column();
    }

    private static function getQuery(SynonymsSearch $search)
    {
        $criteria = [];

        $q = (new \yii\db\Query())
            ->select(['DISTINCT(tp.id)'])
            ->from('tm_parts tp')
            ->join('LEFT JOIN', 'tm_part_synonyms tps', 'tp.id = tps.part_id')
            ->where($criteria)
            ->where("tps.name LIKE '%фла%'")
            ->offset($search->getOffset())
            ->limit($search->limit);

        if ($search->code) {
            $q->where("tp.code LIKE '$search->code'");
        }

        if ($search->ved_name) {
            $q->where("LOWER(tp.raw_name) LIKE LOWER('%{$search->ved_name}%')");
        }

        if ($search->synonym) {
            $q->where("LOWER(tps.name) LIKE LOWER('%{$search->synonym}%')");
        }

        return $q;
    }
}
