<?php

namespace app\commands;

use app\models\FileProcessor\SmartFileProcessor;
use app\traits\ConsoleParamsTrait;
use yii\console\Controller;

class DetectTmPartsCodesController extends Controller
{
    use ConsoleParamsTrait;

    public function actionIndex($filename)
    {
        $this->writeln('start');

        $stat = SmartFileProcessor::instance($filename)
            ->setCodeColumn(4)
            ->setNameColumn(3)
            ->processFile();

        $this->writeln('finish');

        var_export($stat);
    }
}
