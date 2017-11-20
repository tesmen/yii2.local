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
        $rec = new static();
        $rec->name = $title;
        $rec->synonyms = $title;
        $rec->save();

        return $rec;
    }

    public static function tableName()
    {
        return '{{tm_part_types}}';
    }
}
