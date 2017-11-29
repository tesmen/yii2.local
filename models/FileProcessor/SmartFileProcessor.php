<?php

namespace app\models\FileProcessor;

use app\entity\TmPartSynonym;
use app\models\PartsRecognizer\PartCodeDetector;

class SmartFileProcessor extends AbstractFileProcessor
{
    public function processRows()
    {
        $filename = $this->getRealFilePath();

        $data = $this->parseCsvFile($filename);
        $this->stat->totalRows = sizeof($data);

        foreach ($data as &$row) {
            $name = isset($row[$this->nameColumn])
                ? $row[$this->nameColumn]
                : null;

            $existedCode = isset($row[$this->codeColumn])
                ? $row[$this->codeColumn]
                : null;

            if (empty($name) || !empty($existedCode)) {
                $this->stat->skippedRows++;
                continue;
            }

            $code = PartCodeDetector::instance()->detect($name);

            if ($code) {
                $this->stat->detectedRows++;
                $row[$this->codeColumn] = implode('; ', $code);
                $row[29] = 'auto';

                $correlationDetail = TmPartSynonym::findByCode($code);

                if ($correlationDetail) {
                    $row[30] = $correlationDetail->ved_name;
                }
            } else {
                $this->stat->unDetectedRows++;
            }

            $this->stat->processedRows++;
        }

        return $data;
    }
}
