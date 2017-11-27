<?php

namespace app\commands;

use app\models\FileProcessor\SmartFileProcessor;
use app\services\FileService;
use app\traits\ConsoleParamsTrait;
use yii\console\Controller;

class DetectBatchCodesController extends Controller
{
    use ConsoleParamsTrait;

    public function actionIndex()
    {
        $files = scandir(FileService::getBatchDir());

        foreach ($files as $file) {
            if (strpos($file, '.csv')) {
                $this->parseFile($file);
            }
        }
    }

    public function parseFile($filename)
    {
        $this->writeln("---------------------------------------------");
        $this->writeln("start $filename");

        $stat = SmartFileProcessor::instance($filename)
            ->setCodeColumn(4)
            ->setNameColumn(3)
            ->processFile();

        $this->writeln("finish $filename");
        var_export($stat) ;
        $this->writeln('end stat');
    }
}
