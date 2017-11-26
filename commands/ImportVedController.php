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
        $this->writeln('start');

        $data = $this->parseCsvFile($filename);
        $dupes = 0;
        $saved = 0;

        foreach ($data as $row) {
            $kod = empty($row[3])
                ? null
                : mb_strtolower($row[3]);

            $rawName = empty($row[7])
                ? null
                : $row[7];

            if (empty($kod) || empty($rawName)) {
                continue;
            }

            if (TmPart::findOne(['kod' => $kod])) {
                $dupes++;
                continue;
            }

            $rec = new TmPart();
            $rec->kod = $kod;
            $rec->raw_name = $rawName;
            $rec->save();
            TmPartSynonymModel::createSafe(mb_strtolower($rawName), $kod);

            $saved++;
        }

        $all = sizeof($data);
        $this->writeln("Dupes: $dupes");
        $this->writeln("imported: $saved");
        $this->writeln("all: $all");
        $this->writeln('finished');
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }
}
