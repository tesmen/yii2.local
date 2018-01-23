<?php

namespace app\commands;

use app\entity\TmPart;
use app\models\TmPartSynonymModel;
use app\traits\ConsoleParamsTrait;
use yii\console\Controller;

class ImportOboznController extends Controller
{
    use ConsoleParamsTrait;

    public function actionIndex($filename)
    {
        $this->writeln('start');

        $data = $this->parseCsvFile($filename);
        $all = 0;
        $saved = 0;

        foreach ($data as $row) {
            $all++;
            $kod = empty($row[3])
                ? null
                : mb_strtolower($row[3]);

            $obozn = empty($row[6])
                ? null
                : mb_strtolower($row[6]);

            if ($part = TmPart::findOne(['code' => $kod])) {

                $part->obez = $obozn;
                $part->save();
                $saved++;
            }
        }

        $all = sizeof($data);
        $this->writeln("imported: $saved");
        $this->writeln("all: $all");
        $this->writeln('finished');
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }
}
