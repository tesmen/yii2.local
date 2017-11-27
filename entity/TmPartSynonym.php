<?php

namespace app\entity;

use yii\db\ActiveRecord;

/**
 * Class TmPart
 * @package app\models
 *
 * @property $id
 * @property $name
 * @property $part_id
 * @method static TmPartSynonym findOne($c)
 */
class TmPartSynonym extends ActiveRecord
{
    use EntityTrait;
    private static $cache;

    public static function tableName()
    {
        return '{{tm_part_synonyms}}';
    }

    public static function create($title, $partId)
    {
        $e = new static();

        $e->name = $title;
        $e->part_id = $partId;

        return $e->save()
            ? $e
            : false;
    }


    /**
     * @return array|TmPart[]
     */
    public static function getAll()
    {
        return static::find()->all();
    }

    /**
     * @param array $criteria
     * @return array|TmPart[]
     */
    public static function fromCache($criteria)
    {
        $js = json_encode($criteria);

        if (empty(static::$cache[$js])) {
            return static::$cache[$js] = static::findAll($criteria);
        }

        return static::$cache[$js];
    }

    /**
     * @param $code
     * @return TmPartSynonym
     */
    public static function findByCode($code)
    {
        return static::findOne(['code' => $code]);
    }
}
