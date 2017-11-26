<?php

namespace app\entity;

use yii\db\ActiveRecord;

/**
 * Class TmPart
 * @package app\models
 *
 * @property $id
 * @property $name
 * @property $synonyms
 *
 */
class TmPartType extends ActiveRecord
{

    public static function tableName()
    {
        return '{{tm_part_types}}';
    }
    /**
     * @param $title
     * @return bool
     */
    public static function createSafe($title)
    {
        if (static::findOne(['name' => $title])) {
            return false;
        }

        static::create($title);
    }

    /**
     * @param $title
     * @return static
     */
    public static function create($title)
    {
        $rec = new static();
        $rec->name = $title;
        $rec->synonyms = $title;
        $rec->save();

        return $rec;
    }
}
