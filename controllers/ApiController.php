<?php

namespace app\controllers;

use yii\web\Controller;
use \app\entity\TmPartType;

class ApiController extends Controller
{
    public function actionGetPartTypes()
    {
        return $this->asJson(
            ['data' => TmPartType::find()->all()]
        );
    }
}
