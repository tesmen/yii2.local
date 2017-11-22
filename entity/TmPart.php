<?php

namespace app\entity;

use yii\db\ActiveRecord;

/**
 * Class TmPart
 * @package app\models
 *
 * @property $id
 * @property $raw_name
 * @property $name
 * @property $ident_ved
 * @property $kod
 * @property $poz_ved
 * @property $obozn
 * @property $pn
 * @property $dn
 * @property $rmrs
 * @property $part_type_id
 * @property $material_id
 *
 */
class TmPart extends ActiveRecord
{
    private static $cache;

    public static function create($title)
    {
        $e = new static();

        $e->raw_name = $title;
        $e->ob = $title;
        $e->save();

    }

    public static function tableName()
    {
        return '{{tm_parts}}';
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
}
