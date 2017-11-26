<?php

namespace app\models;

use app\entity\TmPartSynonym;

class TmPartSynonymModel
{
    public static function createSafe($name, $code)
    {
        if (TmPartSynonym::findOne(['name' => $name])) {
            return false;
        }

        return TmPartSynonym::create($name, $code);
    }
}
