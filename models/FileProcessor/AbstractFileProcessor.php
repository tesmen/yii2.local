<?php

namespace app\models\FileProcessor;

use app\entity\TmPartSynonym;
use app\models\PartsRecognizer\PartCodeDetector;
use app\services\FileService;

abstract class AbstractFileProcessor
{
    protected $fileName;
    protected $stat;
    protected $batch;
    protected $codeColumn = 3;
    protected $nameColumn = 7;

    private function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->stat = new ProcessedFileStat();
        $this->stat->fileName = $fileName;
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
        return  $this->batch
            ? FileService::getBatchDir($this->fileName)
            : $this->fileName;
    }

    public static function instance($filename)
    {
        return new static($filename);
    }

    public function processAndSaveFile()
    {
        $data = $this->processFile();

        $this->saveCsvFile($data, $this->fileName);

        return $this->stat;
    }

    /**
     * @return array
     */
    abstract function processFile();


    protected function parseCsvFile($file)
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
