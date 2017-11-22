<?php

namespace app\commands;

use app\models\PartsRecognizer\PartCodeDetector;
use yii\console\Controller;

class DetectTmPartsCodesController extends Controller
{
    public function actionIndex($filename)
    {
        $data = $this->parseCsvFile($filename);
        $detected = 0;
        $unDetected = 0;

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


        }

        var_export('detected:' . $detected);
        var_export('undetected:' . $unDetected);

        $this->saveCsvFile($data, $filename);
    }

    public function saveCsvFile(array $rows, $filename)
    {
        $output = fopen('out-' . $filename, 'w');

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
