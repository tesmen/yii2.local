<?php

namespace app\commands;

use app\models\TmPart;
use yii\console\Controller;
use yii\log\Logger;
use Yii;

class EntityController extends Controller
{
    public function actionIndex()
    {
        TmPart::create('daaaaaa!!!');
        var_dump(sizeof( TmPart::find()->all()));
    }

    public function actionLog($message = 'hello world')
    {
        echo $message . "\n";
        $log =\Yii::$app->getLog();
        $logger = $log->getLogger();
        $logger->log('catcat', Logger::LEVEL_INFO);
    }
}
