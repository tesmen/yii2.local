<?php

namespace app\controllers;

use Yii;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\ErrorAction;

class AdminController extends Controller
{
    public function actions()
    {
        return [
            'error'   => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class'           => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST
                    ? 'testme'
                    : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout;

        return $this->render('index');
    }

    public function actionGet()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return 'geeet';
    }

    public function actionPost()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
    }
}
