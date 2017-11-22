<?php

namespace app\commands;

use app\models\FileProcessor\TmCsvFileProcessor;
use app\traits\ConsoleParamsTrait;
use yii\console\Controller;

class DetectTmPartsCodesController extends Controller
{
    use ConsoleParamsTrait;

    public function actionIndex($filename)
    {
        $this->writeln('start');

        $stat = TmCsvFileProcessor::instance($filename)
            ->setCodeColumn(4)
            ->setNameColumn(3)
            ->processFile();

        $this->writeln('finish');

        var_export($stat);
    }
}
