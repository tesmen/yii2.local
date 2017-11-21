<?php

namespace app\commands;

use app\entity\TmPart;
use app\models\PartsRecognizer\PartTypeDetector;
use yii\console\Controller;

class UpdateTmPartsTypesController extends Controller
{
    public function actionIndex()
    {
        $tmParts = TmPart::getAll();
        $detector = PartTypeDetector::instance();

        foreach ($tmParts as $tmPart) {
            $id = $detector->detectPartType($tmPart->raw_name);

            if ($id) {
                $tmPart->part_type_id = $id;
                $tmPart->save();
            }
        }
    }
}
