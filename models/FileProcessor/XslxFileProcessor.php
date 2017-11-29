<?php

namespace app\models\FileProcessor;

use app\entity\TmPart;
use app\models\FileConverter;
use app\models\PartsRecognizer\BySynonymCodeDetector;
use app\services\FileService;

class XslxFileProcessor extends AbstractFileProcessor
{
    protected function prepareFile()
    {
        FileConverter::convertXslxToCsv(
            $this->filePath, FileService::getBatchDir($this->fileName)
        );
    }

    public function processRows()
    {
        $data = $this->parseCsvFile($this->filePath);
        $this->stat->totalRows = sizeof($data);
        var_export($data);
        die;
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
