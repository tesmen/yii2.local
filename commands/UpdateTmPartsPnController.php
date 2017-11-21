<?php

namespace app\commands;

use app\entity\TmPart;
use app\models\PartsRecognizer\PartMaterialDetector;
use app\models\PartsRecognizer\PartPnDetector;
use app\models\PartsRecognizer\PartTypeDetector;
use yii\console\Controller;

class UpdateTmPartsPnController extends Controller
{
    public function actionIndex()
    {
        $tmParts = TmPart::getAll();
        $detector = PartPnDetector::instance();

        foreach ($tmParts as $tmPart) {
            $pn = $detector->detect($tmPart->raw_name);

            if ($pn) {
                var_export($pn);
                $tmPart->pn = $pn;
                $tmPart->save();
            }
        }
    }
}
