<?php

namespace app\commands;

use app\models\PartsRecognizer;
use app\models\TmPart;
use yii\console\Controller;

class FillTmCodesController extends Controller
{
    public function actionIndex($filename)
    {
        $data = $this->parseCsvFile($filename);

        foreach ($data as $row) {
            if (empty($row[7])) {
                continue;
            }

            $recognized = PartsRecognizer::

            $rec = new TmPart();
            $rec->ident_ved = mb_strtolower($row[1]);
            $rec->kod = mb_strtolower($row[3]);
            $rec->poz_ved = mb_strtolower($row[4]);
            $rec->obozn = mb_strtolower($row[6]);
            $rec->raw_name = $row[7];
            $rec->save();
        }
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }

}
