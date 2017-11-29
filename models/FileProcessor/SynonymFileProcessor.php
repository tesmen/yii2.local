<?php

namespace app\models\FileProcessor;

use app\entity\TmPart;
use app\models\PartsRecognizer\BySynonymCodeDetector;

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

            $synonymicPart = BySynonymCodeDetector::instance()->detect($name);

            if ($synonymicPart) {
                $realPart = TmPart::findOne(['id' => $synonymicPart->part_id]);

                $this->stat->detectedRows++;
                $row[$this->codeColumn] = $realPart->code;
                $row[29] = 'auto';
            } else {
                $this->stat->unDetectedRows++;
            }

            $this->stat->processedRows++;
        }

        return $data;
    }
}
