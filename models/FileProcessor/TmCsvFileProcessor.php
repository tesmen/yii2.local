<?php

namespace app\models\FileProcessor;

use app\models\PartsRecognizer\PartCodeDetector;
use app\services\FileService;

class TmCsvFileProcessor
{
    private $fileName;
    private $stat;
    private $codeColumn = 3;
    private $nameColumn = 7;

    private function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->stat = new ProcessedFile();
        $this->stat->fileName = $fileName;
    }

    public static function instance($filename)
    {
        return new static($filename);
    }

    /**
     * @return ProcessedFile
     */
    public function processFile()
    {
        $data = $this->parseCsvFile(FileService::getBatchDir($this->fileName));
        $this->stat->totalRows = sizeof($data);

        foreach ($data as &$row) {
            $name = $row[$this->nameColumn];
            $existedCode = $row[$this->codeColumn];

            if (empty($name) || !empty($existedCode)) {
                $this->stat->skippedRows++;
                continue;
            }

            $code = PartCodeDetector::instance()->detect($name);

            if ($code) {
                $this->stat->detectedRows++;
                $row[$this->codeColumn] = implode('; ', $code);
            } else {
                $this->stat->unDetectedRows++;
            }

            $this->stat->processedRows++;
        }

        $this->saveCsvFile($data, $this->fileName);

        return $this->stat;
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }

    public function saveCsvFile(array $rows, $filename)
    {
        $output = fopen(FileService::getOutputDir($filename), 'w');

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
     * @return TmCsvFileProcessor
     */
    public function setCodeColumn($codeColumn)
    {
        $this->codeColumn = $codeColumn;

        return $this;
    }

    /**
     * @param int $nameColumn
     * @return TmCsvFileProcessor
     */
    public function setNameColumn($nameColumn)
    {
        $this->nameColumn = $nameColumn;

        return $this;
    }


}
