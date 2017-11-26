<?php

namespace app\commands;

use app\entity\TmPart;
use app\traits\ConsoleParamsTrait;
use yii\console\Controller;

class ImportCorrelationMapController extends Controller
{
    use ConsoleParamsTrait;

    public function actionIndex($filename)
    {
        $data = $this->parseCsvFile($filename);
        $dupes = 0;
        $saved = 0;

        foreach ($data as $row) {
            $tmName = mb_strtolower($row[0]);
            $code = mb_strtolower($row[1]);
            $vedName = mb_strtolower($row[2]);

            if (empty($tmName)) {
                continue;
            }
            $res = \app\entity\TmPartSynonym::createSafe($tmName, $code, $vedName);

            if ($res) {
                $saved++;
            } else {
                $dupes++;
            }
        }

        $all = sizeof($data);
        $this->writeln("Dupes: $dupes");
        $this->writeln("imported: $saved");
        $this->writeln("all: $all");
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }

}
