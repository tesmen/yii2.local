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
//Отвод 90-133х5-20 ГОСТ 30753-2001, отеч. РМРС

        foreach ($tmParts as $row) {
            $words = TextManipulator::createFromString($row->raw_name)
                ->getNaturalWords();

            $length = isset($words[1]) && TextManipulator::isWord($words[1])
                ? 2
                : 1;

            $variant = array_splice($words, 0, $length);
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
