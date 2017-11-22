<?php

namespace app\commands;

use app\models\PartsRecognizer\PartCodeDetector;
use yii\console\Controller;

class DetectBatchCodesController extends Controller
{

    public function getBatchDir()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '/batch/';
    }

    public function actionIndex()
    {
        $files = scandir($this->getBatchDir());

        foreach ($files as $file) {
            if (strpos($file, '.csv')) {
                $this->parseFile($file);
            }
        }
    }

    private function parseFile($filename)
    {
        $data = $this->parseCsvFile($this->getBatchDir() . $filename);
        $detected = 0;
        $unDetected = 0;
        $all = 0;

        foreach ($data as &$row) {
            $name = $row[3];
            $existedCode = $row[4];

            if (empty($name) || !empty($existedCode)) {
                continue;
            }

            $code = PartCodeDetector::instance()->detect($name);

            if ($code) {
                $detected++;
                $row[4] = implode('; ', $code);
            } else {
                $unDetected++;
            }

            $all++;
        }

        echo($filename);
        echo(" detected: $detected / {$all}");
        echo(PHP_EOL);

        $this->saveCsvFile($data, $filename);
    }

    public function saveCsvFile(array $rows, $filename)
    {
        $output = fopen($this->getBatchDir() . '/output/' . $filename, 'w');

        if (true) {
            $BOM = chr(0xEF) . chr(0xBB) . chr(0xBF); // excel and others compatibility
            fputs($output, $BOM);
        }

        foreach ($rows as $row) {
            fputcsv($output, $row, ',');
        }

        fclose($output);
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }
}
