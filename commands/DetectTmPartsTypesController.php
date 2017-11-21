<?php

namespace app\commands;

use app\entity\TmPart;
use app\models\PartsRecognizer\PartTypeDetector;
use yii\console\Controller;

class DetectTmPartsTypesController extends Controller
{
    public function actionIndex()
    {
        $tmParts = TmPart::getAll();
        $detector = new PartTypeDetector();

        foreach ($tmParts as $tmPart) {
            $id = $detector->detectPartType($tmPart->raw_name);

            if ($id) {
                $tmPart->part_type_id = $id;
                $tmPart->save();
            }
        }
    }
}
