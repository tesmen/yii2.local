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
        $c = 0;
        $tmParts = TmPart::getAll();

        foreach ($tmParts as $row) {
            $words = PartNameStripper::stripToArray($row->raw_name);
            $variant = reset($words);

            if (empty($variant)) {
                continue;
            }

            $res = TmPartType::createSafe($variant);

            if ($res) {
                $this->writeln($variant);
            }

            if ($c % 100 === 0) {
                $this->writeln($c);
            }
        }
    }
}
