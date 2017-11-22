<?php

namespace app\entity;

use yii\db\ActiveRecord;

/**
 * Class TmPart
 * @package app\models
 *
 * @property $id
 * @property $tm_name
 * @property $code
 * @property $ved_name
 *
 */
class CorrelationMap extends ActiveRecord
{
    private static $cache;

    public static function create($title, $code, $vedName)
    {
        $e = new static();

        $e->tm_name = $title;
        $e->code = $code;
        $e->ved_name = $vedName;

        return $e->save()
            ? $e
            : false;
    }

    public static function createSafe($title, $code, $vedName)
    {
        if (static::findOne(['code' => $code])) {
            return false;
        }

        return static::create($title, $code, $vedName);
    }

    public static function tableName()
    {
        return '{{tm_correlation_map}}';
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
     * @return CorrelationMap
     */
    public static function findByCode($code)
    {
        return static::findOne(['code' => $code]);
    }
}
