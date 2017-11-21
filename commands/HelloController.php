<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\entity\TmPart;
use app\models\PartsRecognizer\PartCodeDetector;
use app\util\TextManipulator;
use yii\console\Controller;
use yii\log\Logger;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        $a = \app\models\PartsRecognizer\PartPnDetector::instance()->detect(
            'фланец трубный плоский приварной стальной Ду25 Ру16'
        );
        var_export($a);
    }
}
