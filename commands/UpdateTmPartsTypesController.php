<?php

namespace app\commands;

use app\entity\TmPart;
use app\models\PartsRecognizer\PartTypeDetector;
use app\traits\ConsoleParamsTrait;
use yii\console\Controller;

class UpdateTmPartsTypesController extends Controller
{
    use ConsoleParamsTrait;

    public function actionIndex()
    {
        $c = 0;
        $tmParts = TmPart::getAll();
        $detector = PartTypeDetector::instance();

        foreach ($tmParts as $tmPart) {
            $id = $detector->detect($tmPart->raw_name);

            if ($id) {
                $tmPart->part_type_id = $id;
                $tmPart->save();
            }

            if ($c % 100 === 0) {
                $this->writeln($c);
            }
        }
    }
}
