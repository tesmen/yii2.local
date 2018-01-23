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
 * @property $code
 * @property $poz_ved
 * @property $pn
 * @property $dn
 * @property $obez
 * @property $rmrs
 * @property $part_type_id
 * @property $material_id
 *
 */
class TmPart extends ActiveRecord
{
    use EntityTrait;

    private static $cache;

    public static function tableName()
    {
        return '{{tm_parts}}';
    }

    public static function create($title)
    {
        $e = new static();

        $e->raw_name = $title;
        $e->save();

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
