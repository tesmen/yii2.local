<?php

namespace app\models\FileProcessor;

use app\entity\TmPartSynonym;
use app\models\PartsRecognizer\PartCodeDetector;
use app\services\FileService;

abstract class AbstractFileProcessor
{
    protected $filePath;
    protected $fileName;
    protected $stat;
    protected $batch;
    protected $codeColumn = 3;
    protected $nameColumn = 7;

    private function __construct($filePath)
    {
        $parts = explode(DIRECTORY_SEPARATOR, $filePath);
        $this->filePath = $filePath;
        $this->fileName = end($parts);

        $this->stat = new ProcessedFileStat();
        $this->stat->fileName = $filePath;
    }

    /**
     * @param mixed $batch
     * @return AbstractFileProcessor
     */
    public function setBatch($batch)
    {
        $this->batch = (bool)$batch;

        return $this;
    }

    /**
     * @return string
     */
    public function getRealFilePath()
    {
        return $this->batch
            ? FileService::getBatchDir($this->filePath)
            : $this->filePath;
    }

    public static function instance($filePath)
    {
        return new static($filePath);
    }

    public function processAndSaveFile()
    {
        $data = $this->processRows();

        $this->saveCsvFile($data, $this->filePath);

        return $this->stat;
    }

    public function processFile()
    {
        $this
            ->prepareFile()
            ->getRows()
            ->processRows();
    }

    /**
     * @return array
     */
    abstract function processRows();

    /**
     * @return $this
     */
    protected function prepareFile()
    {
        return $this;
    }

    protected function getRows()
    {
        return $this;
    }

    protected function postProcess()
    {

        return $this;
    }

    protected function getFileName()
    {

        return $this->fileName;
    }

    protected function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }

    public function saveCsvFile(array $rows, $filename)
    {
        $realFilename = $this->batch
            ? FileService::getOutputDir($filename)
            : $this->filePath;

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
     * @return AbstractFileProcessor
     */
    public function setCodeColumn($codeColumn)
    {
        $this->codeColumn = $codeColumn;

        return $this;
    }

    /**
     * @param int $nameColumn
     * @return AbstractFileProcessor
     */
    public function setNameColumn($nameColumn)
    {
        $this->nameColumn = $nameColumn;

        return $this;
    }


}
