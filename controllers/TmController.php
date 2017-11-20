<?php

namespace app\controllers;

use app\entity\TmPartType;
use app\traits\ControllerTrait;
use yii\web\Controller;

class TmController extends Controller
{
    use ControllerTrait;

    public function actionPartTypes()
    {
        return $this->render('tm-part-types');
    }

    public function actionGetPartTypes()
    {
        return $this->asJson(
            ['data' => TmPartType::find()->all()]
        );
    }

    public function actionCreatePartType()
    {
        $name = $this->getRequestParams('name');

        return $this->asJson(TmPartType::create($name));
    }

    public function actionTogglePartTypeActive()
    {
        $name = $this->getRequestParams('id');

        return $this->asJson(TmPartType::create($name));
    }

    public function actionPost()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
    }
}
