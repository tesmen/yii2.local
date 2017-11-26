<?php

namespace app\commands;

use app\entity\TmPart;
use app\models\TmPartSynonymModel;
use app\traits\ConsoleParamsTrait;
use yii\console\Controller;

class ImportVedController extends Controller
{
    use ConsoleParamsTrait;

    public function actionIndex($filename)
    {
        $data = $this->parseCsvFile($filename);
        $dupes = 0;
        $saved = 0;

        foreach ($data as $row) {
            $kod = mb_strtolower($row[3]);
            $rawName = $row[7];
            if (empty($kod)) {
                continue;
            }

            $detailName = mb_strtolower($rawName);

            if (TmPart::findOne(['kod' => $kod])) {
                $dupes++;
                continue;
            }

            $rec = new TmPart();
            $rec->kod = $kod;
            $rec->raw_name = $detailName;
            $rec->save();
            TmPartSynonymModel::createSafe($detailName, $kod);

            $saved++;
        }

        $all = sizeof( $data);
        $this->writeln("Dupes: $dupes");
        $this->writeln("imported: $saved");
        $this->writeln("all: $all");
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }

}
