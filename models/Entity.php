<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Entity
 * @package app\models
 * @property int $id
 * @property int $title
 * @property int $data
 * @property int $created
 * @property int $comment
 */
class Entity extends ActiveRecord
{
    public static function create($title)
    {
        $e = new static();

        $e->name = $title;
        $e->params = $title;
        $e->save();

    }

    public static function tableName()
    {
        return '{{tm_parts}}';
    }
}
