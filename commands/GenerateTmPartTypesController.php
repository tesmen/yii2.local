<?php

namespace app\commands;

use app\entity\TmPart;
use app\entity\TmPartType;
use app\models\PartsRecognizer\PartNameStripper;
use app\traits\ConsoleParamsTrait;
use app\util\TextManipulator;
use yii\console\Controller;

class GenerateTmPartTypesController extends Controller
{
    use ConsoleParamsTrait;

    public function actionIndex()
    {
        $tmParts = TmPart::getAll();

        foreach ($tmParts as $row) {
            $words = PartNameStripper::stripToArray($row);

            $variant = array_splice($words, 0, 1);
            $partTypeName = mb_strtolower(implode(' ', $variant));
            $res = TmPartType::createSafe($partTypeName);

            if ($res) {
                $this->writeln($partTypeName);
            }
        }
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }

}
