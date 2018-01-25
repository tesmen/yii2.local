<?php

namespace app\models\FileProcessor;

use app\entity\TmPart;
use app\models\FileConverter;
use app\models\PartsRecognizer\ByOboznCodeDetector;
use app\models\PartsRecognizer\BySynonymCodeDetector;
use app\services\FileService;

class KvzMarker extends AbstractFileProcessor
{
    /**
     * @param array $data
     * @return array
     */
    public function mark($data)
    {
        foreach ($data as &$row) {
            $row[$this->kvzColumn] = $this->getKvzCode($row);
        }

        return $data;
    }

    public function processRows()
    {
        // mock
        return null;
    }

    /**
     * @param $row
     * @return bool|int
     */
    private function getKvzCode(&$row)
    {
        $kvz = null;
        $codeStr = (string)$row[$this->codeColumn];
        $obozStr = (string)$row[$this->oboznColumn];
        $oboznIsEmpty = ($obozStr === '***' || empty($obozStr));

        if (empty($codeStr)) {
            return false;
        }

        $firstCodeNumber = isset($codeStr[0])
            ? (int)$codeStr[0]
            : null;


        switch (true) {
            case (0 === $firstCodeNumber):
                $kvz = 0;
                break;
            case (1 === $firstCodeNumber && $oboznIsEmpty):
                $kvz = 1;
                break;
            case (1 === $firstCodeNumber && !$oboznIsEmpty):
                $kvz = 3;
                break;
            case ($firstCodeNumber >= 3):
                $kvz = 3;
                break;
        }

//        $emptyStr = $oboznIsEmpty
//            ? 1
//            : 0;
//        echo("$obozStr $codeStr first=$firstCodeNumber isempty=$emptyStr $kvz <br>");

        return $kvz;
    }
}
