<?php

namespace app\models;

use app\entity\TmPart;
use app\entity\TmPartSynonym;
use app\models\Search\SynonymsSearch;

class TmPartModel
{

    public static function updateObez($id, $obez)
    {
        $rec = TmPart::findById($id);

        if (!$rec) {
            return false;
        }

        $rec->obez = $obez;

        return $rec->save();
    }

}
