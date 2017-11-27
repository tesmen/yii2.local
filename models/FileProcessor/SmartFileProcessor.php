<?php

namespace app\models\FileProcessor;

use app\entity\TmPartSynonym;
use app\models\PartsRecognizer\PartCodeDetector;
use app\services\FileService;

class SmartFileProcessor
{
    private $fileName;
    private $stat;
    private $batch;
    private $codeColumn = 3;
    private $nameColumn = 7;

    private function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->stat = new ProcessedFileStat();
        $this->stat->fileName = $fileName;
    }

    /**
     * @param mixed $batch
     * @return SmartFileProcessor
     */
    public function setBatch($batch)
    {
        $this->batch = (bool)$batch;

        return $this;
    }

    public static function instance($filename)
    {
        return new static($filename);
    }

    /**
     * @return ProcessedFileStat
     */
    public function processFile()
    {
        $filename = $this->batch
            ? FileService::getBatchDir($this->fileName)
            : $this->fileName;

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

        $this->saveCsvFile($data, $this->fileName);

        return $this->stat;
    }

    private function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }

    public function saveCsvFile(array $rows, $filename)
    {
        $realFilename = $this->batch
            ? FileService::getOutputDir($filename)
            : $this->fileName;

        $output = fopen($realFilename, 'w');

        if (true) {
            $BOM = chr(0xEF) . chr(0xBB) . chr(0xBF); // excel and others compatibility
            fputs($output, $BOM);
        }

        foreach ($rows as $row) {
            fputcsv($output, $row, ',');
        }

        fclose($output);
    }

    /**
     * @param int $codeColumn
     * @return SmartFileProcessor
     */
    public function setCodeColumn($codeColumn)
    {
        $this->codeColumn = $codeColumn;

        return $this;
    }

    /**
     * @param int $nameColumn
     * @return SmartFileProcessor
     */
    public function setNameColumn($nameColumn)
    {
        $this->nameColumn = $nameColumn;

        return $this;
    }


}
