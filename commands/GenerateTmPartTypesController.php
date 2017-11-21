<?php

namespace app\commands;

use app\entity\TmPart;
use app\entity\TmPartType;
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
            $words = TextManipulator::createFromString($row->raw_name)
                ->getNaturalWords();

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
