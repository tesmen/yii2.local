<?php

namespace app\models\FileProcessor;

use app\entity\TmPartSynonym;
use app\models\PartsRecognizer\BySynonymCodeDetector;
use app\models\PartsRecognizer\PartCodeDetector;
use app\services\FileService;

class SynonymFileProcessor extends AbstractFileProcessor
{
    public function processFile()
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

            $code = BySynonymCodeDetector::instance()->detect($name);

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
