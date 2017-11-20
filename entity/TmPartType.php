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
    public static function create($title)
    {
        $e = new static();

        $e->name = $title;
        $e->synonyms = $title;
        $e->save();

    }

    public static function tableName()
    {
        return '{{tm_part_types}}';
    }
}
