<?php

namespace app\commands;

use app\entity\TmPart;
use app\models\PartsRecognizer\PartDnDetector;
use app\models\PartsRecognizer\PartMaterialDetector;
use app\models\PartsRecognizer\PartPnDetector;
use app\models\PartsRecognizer\PartTypeDetector;
use yii\console\Controller;

class UpdateTmPartsDnController extends Controller
{
    public function actionIndex()
    {
        $tmParts = TmPart::getAll();
        $detector = PartDnDetector::instance();

        foreach ($tmParts as $tmPart) {
            $dn = $detector->detect($tmPart->raw_name);

            if ($dn) {
                var_export($dn);
                $tmPart->dn = $dn;
                $tmPart->save();
            }
        }
    }
}
