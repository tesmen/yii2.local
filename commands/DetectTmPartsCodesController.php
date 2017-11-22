<?php

namespace app\commands;

use app\entity\TmPart;
use yii\console\Controller;

class DetectTmPartsCodesController extends Controller
{
    public function actionIndex($filename)
    {
        $data = $this->parseCsvFile($filename);
        $dupes = 0;


        foreach ($data as $row) {
            if (empty($row[7])) {
                continue;
            }

            $kod = mb_strtolower($row[3]);
            $detailName = $row[7];

            if (TmPart::findOne(['kod' => $kod])) {
                $dupes++;
                continue;
            }

            $rec = new TmPart();
            $rec->ident_ved = mb_strtolower($row[1]);
            $rec->kod = $kod;
            $rec->poz_ved = mb_strtolower($row[4]);
            $rec->obozn = mb_strtolower($row[6]);
            $rec->raw_name = $detailName;
            $rec->save();
        }

        var_export('Dupes:' . $dupes);
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }

}
