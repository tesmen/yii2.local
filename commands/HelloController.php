<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\entity\TmPart;
use app\models\PartsRecognizer\PartCodeDetector;
use app\models\PartsRecognizer\PartMaterialDetector;
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
//        $a = PartMaterialDetector::instance()->detect(
//            'Затвор поворотный фланцевый Ду125, Ру10 Econ fig.6610,  ковкий чугун, импорт, морской регистр -'
//        );
//        var_export($a);
//        die;
        $code = \app\models\PartsRecognizer\PartCodeDetector::instance()->detect(
            'Затвор поворотный межфланцевый,  Ду125, Ру10 тип "баттерфляй" GGG-40.3'
        );
        var_export($code);
    }
}
