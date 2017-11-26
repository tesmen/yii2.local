<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\entity\TmPart;
use app\entity\TmPartSynonym;
use app\models\PartsRecognizer\PartCodeDetector;
use app\models\PartsRecognizer\PartMaterialDetector;
use app\models\TmPartSynonymModel;
use app\util\TextManipulator;
use yii\console\Controller;
use yii\log\Logger;
use Yii;


class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
//        $code = \app\models\PartsRecognizer\PartCodeDetector::instance()->detect(
//            'Труба 140х8 ГОСТ8732-78/В10 ГОСТ8731-74 (внутренний диаметр расточить до 125)'
//        );

        $code = TmPartSynonymModel::createSafe('one kpart',33);
        var_export($code);
    }
}
