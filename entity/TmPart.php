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
 *
 */
class TmPart extends ActiveRecord
{
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
}
