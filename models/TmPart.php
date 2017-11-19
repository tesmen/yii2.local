<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class TmPart
 * @package app\models
 *
 * @property $id
 * @property $name
 * @property $ident_ved
 * @property $kod
 * @property $poz_ved
 * @property $obozn
 *
 */
class TmPart extends ActiveRecord
{
    public static function create($title)
    {
        $e = new static();

        $e->name = $title;
        $e->ob = $title;
        $e->save();

    }

    public static function tableName()
    {
        return '{{tm_parts}}';
    }
}
