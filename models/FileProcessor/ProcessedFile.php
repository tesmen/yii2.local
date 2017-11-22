<?php

namespace app\models\FileProcessor;


class ProcessedFile
{
    public $fileName;
    public $totalRows = 0;
    public $processedRows = 0;
    public $skippedRows = 0;
    public $detectedRows = 0;
    public $unDetectedRows = 0;

}
