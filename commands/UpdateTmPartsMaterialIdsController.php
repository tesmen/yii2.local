<?php

namespace app\commands;

use app\entity\TmPart;
use app\models\PartsRecognizer\PartMaterialDetector;
use app\models\PartsRecognizer\PartTypeDetector;
use yii\console\Controller;

class UpdateTmPartsMaterialIdsController extends Controller
{
    public function actionIndex()
    {
        $tmParts = TmPart::getAll();
        $detector = PartMaterialDetector::instance();

        foreach ($tmParts as $tmPart) {
            $id = $detector->detect($tmPart->raw_name);

            if ($id) {
                $tmPart->material_id = $id;
                $tmPart->save();
            }
        }
    }
}
